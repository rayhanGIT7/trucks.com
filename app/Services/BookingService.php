<?php

namespace App\Services;

use App\Core\Validator;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Truck;
use App\Models\User;

/**
 * Booking rules: build a trip, calculate the fare, create and cancel bookings.
 */
class BookingService
{
    private DistanceCalculator $distanceCalculator;
    private FareCalculator $fareCalculator;

    public function __construct()
    {
        $this->distanceCalculator = new DistanceCalculator();
        $this->fareCalculator = new FareCalculator();
    }

    /**
     * Build a Trip from the pickup / dropoff / date the customer chose.
     * Throws ServiceException (with a message for the customer) if the input is wrong.
     */
    public function makeTrip(array $input): Trip
    {
        $validator = new Validator($input, [
            'pickup'  => 'required|integer',
            'dropoff' => 'required|integer|different:pickup',
            'date'    => 'required|date|future_or_today',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            throw new ServiceException(reset($errors)); // first error message
        }

        $pickup = Location::findActive((int) $input['pickup']);
        $dropoff = Location::findActive((int) $input['dropoff']);
        if ($pickup === null || $dropoff === null) {
            throw new ServiceException('Please choose a valid pickup and destination.');
        }

        $distanceKm = $this->distanceCalculator->between($pickup, $dropoff);

        return new Trip($pickup, $dropoff, $input['date'], $distanceKm);
    }

    public function calculateFare(Truck $truck, Trip $trip): Fare
    {
        return $this->fareCalculator->calculate($truck, $trip->distanceKm);
    }

    /**
     * Create a pending booking.
     * Distance and fare are always calculated here on the server, never taken from the browser.
     *
     * $details = contact_name, contact_phone, pickup_address, dropoff_address, shifting_type, notes
     */
    public function create(User $user, Truck $truck, Trip $trip, array $details): Booking
    {
        if (!$truck->isFreeOn($trip->date)) {
            throw new ServiceException('Sorry, this truck is not available on ' . format_date($trip->date) . '. Please choose another truck.');
        }

        $fare = $this->calculateFare($truck, $trip);

        $notes = $details['notes'];
        if ($notes === '') {
            $notes = null;
        }

        return Booking::create([
            'booking_no'          => Booking::generateBookingNo(),
            'user_id'             => $user->id,
            'truck_id'            => $truck->id,
            'pickup_location_id'  => $trip->pickup->id,
            'dropoff_location_id' => $trip->dropoff->id,
            'pickup_address'      => $details['pickup_address'],
            'dropoff_address'     => $details['dropoff_address'],
            'shifting_type'       => $details['shifting_type'],
            'shifting_date'       => $trip->date,
            'contact_name'        => $details['contact_name'],
            'contact_phone'       => $details['contact_phone'],
            'notes'               => $notes,
            'distance_km'         => $fare->distanceKm,
            'base_fare'           => $fare->baseFare,
            'per_km_rate'         => $fare->perKmRate,
            'total_fare'          => $fare->total,
            'status'              => Booking::STATUS_PENDING,
        ]);
    }

    public function cancelByCustomer(Booking $booking): void
    {
        if (!$booking->isPending()) {
            throw new ServiceException('Only unpaid (pending) bookings can be cancelled.');
        }
        $booking->cancel();
    }
}
