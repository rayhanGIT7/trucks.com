<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\HttpException;
use App\Core\Validator;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Truck;
use App\Models\User;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\ServiceException;
use App\Services\Trip;

class BookingController extends Controller
{
    private BookingService $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }

    /** Booking history. */
    public function index(): void
    {
        $user = $this->requireLogin();

        $this->view('bookings/index', [
            'bookings' => Booking::forUser($user->id),
        ]);
    }

    /** Booking form for a chosen truck + trip. */
    public function create(): void
    {
        $user = $this->requireLogin();
        $truck = $this->truckFromInput();
        $trip = $this->tripFromInput();

        $this->view('bookings/create', [
            'user'  => $user,
            'truck' => $truck,
            'trip'  => $trip,
            'fare'  => $this->bookingService->calculateFare($truck, $trip),
        ]);
    }

    public function store(): void
    {
        $user = $this->requireLogin();
        $truck = $this->truckFromInput();
        $trip = $this->tripFromInput();

        $validator = new Validator($_POST, [
            'contact_name'    => 'required|max:100',
            'contact_phone'   => 'required|phone',
            'pickup_address'  => 'required|max:255',
            'dropoff_address' => 'required|max:255',
            'shifting_type'   => 'required|in:' . implode(',', Booking::SHIFTING_TYPES),
            'notes'           => 'max:1000',
        ]);
        if ($validator->fails()) {
            $this->backWithErrors($validator->errors());
        }

        try {
            $booking = $this->bookingService->create($user, $truck, $trip, [
                'contact_name'    => $this->input('contact_name'),
                'contact_phone'   => $this->input('contact_phone'),
                'pickup_address'  => $this->input('pickup_address'),
                'dropoff_address' => $this->input('dropoff_address'),
                'shifting_type'   => $this->input('shifting_type'),
                'notes'           => $this->input('notes'),
            ]);
        } catch (ServiceException $e) {
            $this->flash('danger', $e->getMessage());
            $this->redirect('/trucks', $trip->toQuery());
        }

        $this->flash('success', 'Booking created. Please complete the payment to confirm it.');
        $this->redirect('/bookings/' . $booking->id);
    }

    /** Booking details + status + payments. */
    public function show(int $id): void
    {
        $user = $this->requireLogin();
        $booking = $this->findOwnBooking($id, $user);

        $this->view('bookings/show', [
            'booking'       => $booking,
            'payments'      => Payment::forBooking($booking->id),
            'paymentResult' => $_GET['payment'] ?? '',
        ]);
    }

    /** "Pay Now" → redirect to SSLCommerz. */
    public function pay(int $id): void
    {
        $user = $this->requireLogin();
        $booking = $this->findOwnBooking($id, $user);

        try {
            $gatewayUrl = (new PaymentService())->start($booking, $user);
        } catch (ServiceException $e) {
            $this->flash('danger', $e->getMessage());
            $this->redirect('/bookings/' . $booking->id);
        }

        header('Location: ' . $gatewayUrl);
        exit;
    }

    public function cancel(int $id): void
    {
        $user = $this->requireLogin();
        $booking = $this->findOwnBooking($id, $user);

        try {
            $this->bookingService->cancelByCustomer($booking);
            $this->flash('success', 'Booking cancelled.');
        } catch (ServiceException $e) {
            $this->flash('danger', $e->getMessage());
        }
        $this->redirect('/bookings/' . $booking->id);
    }

    /** Customers can only see their own bookings. */
    private function findOwnBooking(int $id, User $user): Booking
    {
        $booking = Booking::findWithDetails($id);
        if ($booking === null || !$booking->belongsTo($user)) {
            throw new HttpException(404, 'Booking not found.');
        }
        return $booking;
    }

    /** The truck the customer chose (?truck=ID). If missing, go back to the truck list. */
    private function truckFromInput(): Truck
    {
        $truck = Truck::find((int) $this->input('truck'));
        if ($truck === null) {
            $this->flash('warning', 'Please choose a truck first.');
            $this->redirect('/trucks');
        }
        return $truck;
    }

    /** The trip the customer chose (pickup, dropoff, date). If wrong, go back to the truck list. */
    private function tripFromInput(): Trip
    {
        try {
            return $this->bookingService->makeTrip([
                'pickup'  => $this->input('pickup'),
                'dropoff' => $this->input('dropoff'),
                'date'    => $this->input('date'),
            ]);
        } catch (ServiceException $e) {
            $this->flash('warning', $e->getMessage());
            $this->redirect('/trucks');
        }
    }
}
