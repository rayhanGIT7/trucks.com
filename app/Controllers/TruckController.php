<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Location;
use App\Models\Truck;
use App\Services\BookingService;
use App\Services\ServiceException;

class TruckController extends Controller
{
    private BookingService $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }

    /**
     * Truck list.
     * Without a search: show all trucks.
     * With pickup / dropoff / date: show only free trucks, each with its fare.
     */
    public function index(): void
    {
        $trip = null;
        $error = '';

        if (isset($_GET['pickup'])) {
            try {
                $trip = $this->bookingService->makeTrip($_GET);
            } catch (ServiceException $e) {
                $error = $e->getMessage();
            }
        }

        if ($trip === null) {
            $trucks = Truck::all();
        } else {
            $trucks = Truck::availableOn($trip->date);
        }

        // Fare of every truck for this trip, by truck id.
        $fares = [];
        if ($trip !== null) {
            foreach ($trucks as $truck) {
                $fares[$truck->id] = $this->bookingService->calculateFare($truck, $trip);
            }
        }

        $this->view('trucks/index', [
            'trucks'    => $trucks,
            'trip'      => $trip,
            'fares'     => $fares,
            'error'     => $error,
            'locations' => Location::active(),
        ]);
    }

    /** Truck details. If a trip is given, also show the fare. */
    public function show(int $id): void
    {
        $truck = Truck::findOrFail($id);
        $trip = null;
        $fare = null;

        if (isset($_GET['pickup'])) {
            try {
                $trip = $this->bookingService->makeTrip($_GET);
                $fare = $this->bookingService->calculateFare($truck, $trip);
            } catch (ServiceException $e) {
                $this->flash('warning', $e->getMessage());
                $this->redirect('/trucks/' . $truck->id);
            }
        }

        if ($trip === null) {
            $isAvailable = $truck->is_available;
        } else {
            $isAvailable = $truck->isFreeOn($trip->date);
        }

        $this->view('trucks/show', [
            'truck'       => $truck,
            'trip'        => $trip,
            'fare'        => $fare,
            'isAvailable' => $isAvailable,
            'locations'   => Location::active(),
        ]);
    }
}
