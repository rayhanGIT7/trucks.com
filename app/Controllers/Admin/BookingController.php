<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\HttpException;
use App\Models\Booking;
use App\Models\Payment;

class BookingController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $status = in_array($this->input('status'), Booking::STATUSES, true) ? $this->input('status') : '';

        $this->view('admin/bookings/index', [
            'bookings' => Booking::search($status, $this->input('q')),
            'status'   => $status,
            'keyword'  => $this->input('q'),
        ], 'layouts/admin');
    }

    public function show(int $id): void
    {
        $this->requireAdmin();
        $booking = Booking::findWithDetails($id);
        if ($booking === null) {
            throw new HttpException(404);
        }

        $this->view('admin/bookings/show', [
            'booking'  => $booking,
            'payments' => Payment::forBooking($booking->id),
        ], 'layouts/admin');
    }

    public function updateStatus(int $id): void
    {
        $this->requireAdmin();
        $booking = Booking::findOrFail($id);
        $newStatus = $this->input('status');

        if (!in_array($newStatus, $booking->nextStatusesForAdmin(), true)) {
            $this->flash('danger', "A {$booking->status} booking cannot be changed to \"$newStatus\".");
            $this->redirect('/admin/bookings/' . $id);
        }

        if ($newStatus === Booking::STATUS_COMPLETED) {
            $booking->complete();
        } else {
            $booking->cancel();
        }

        $this->flash('success', "Booking {$booking->booking_no} marked as $newStatus.");
        $this->redirect('/admin/bookings/' . $id);
    }
}
