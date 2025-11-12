<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreCheckBookingRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\BookingTransaction;
use App\Models\Ticket;
use App\Services\BookingService;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function booking(Ticket $ticket)
    {
        return view('front.booking', compact('ticket'));
    }

    public function bookingStore(Ticket $ticket, StoreBookingRequest $request)
    {
        $validated = $request->validated();

        // Check if 'total_participant' exists in validated data
        $totalParticipants = isset($validated['total_participant']) ? $validated['total_participant'] : 0;

        $totals = $this->bookingService->calculateTotals($ticket->id, $totalParticipants);

        $this->bookingService->storeBookingSession($ticket, $validated, $totals);

        return redirect()->route('front.payment');
    }

    public function payment()
    {
        $data = $this->bookingService->payment();
        return view('front.payment', $data);
    }

    public function paymentStore(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        $bookingTransactionId = $this->bookingService->paymentStore($validated);

        if (!$bookingTransactionId) {
            return redirect()->route('front.booking_finished', $bookingTransactionId);
        }
        return redirect()->route('front.booking_finished', $bookingTransactionId);
    }

    public function bookingFinished(BookingTransaction $bookingTransaction)
    {
        return view('front.booking_finished', compact('bookingTransaction'));
    }

    public function checkBooking()
    {
        return view('front.check_booking');
    }

    public function checkBookingDetails(StoreCheckBookingRequest $request)
    {
        $validated = $request->validated();

        $bookingDetails = $this->bookingService->getBookingDdetails($validated);

        if ($bookingDetails) {
            return view('front.check_booking_details', compact('bookingDetails'));
        }
        return redirect()->route('front.check_booking')->withErrors(['errors' => 'Booking not found, please check your booking code and email.']);
    }
}
