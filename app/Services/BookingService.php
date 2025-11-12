<?php

namespace App\Services;

use App\Jobs\SendBookingConfirmedEmail;
use App\Models\BookingTransaction;
use App\Models\Ticket;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BookingService
{
      protected $bookingRepository;
      protected $ticketRepository;

      public function __construct(TicketRepositoryInterface $ticketRepository, BookingRepositoryInterface $bookingRepository)
      {
            $this->ticketRepository = $ticketRepository;
            $this->bookingRepository = $bookingRepository;
      }

      public function getBookingDdetails(array $validated)
      {
            return $this->bookingRepository->findByTrxIdAndPhoneNumber($validated['booking_trx_id'], $validated['phone_number']);
      }

      public function calculateTotals($ticketId, $totalParticipant)
      {
            $price = $this->ticketRepository->getPrice($ticketId);

            $subTotal = $totalParticipant * $price;
            $totalAmount = $subTotal;

            return [
                  'subTotal' => $subTotal,
                  'totalAmount' => $totalAmount
            ];
      }

      public function storeBookingSession($ticket, $validatedData, $totals)
      {
            session()->put('booking', [
                  'ticket_id' => $ticket->id,
                  'name' => $validatedData['name'],
                  'email' => $validatedData['email'],
                  'phone_number' => $validatedData['phone_number'],
                  'started_at' => $validatedData['started_at'],
                  'total_participant' => $validatedData['total_participant'],
                  'sub_total' => $totals['subTotal'],
                  'total_amount' => $totals['totalAmount'],
            ]);
      }

      public function payment()
      {
            $booking = session('booking');
            $ticket = $this->ticketRepository->find($booking['ticket_id']);

            return compact('booking', 'ticket');
      }

      public function paymentStore(array $validated)
      {
            $booking = session('booking');
            $bookingTransactionId = null;


            DB::transaction(function () use ($validated, &$bookingTransactionId, $booking) {

                  if (!isset($validated['proof'])) {
                        $proofPath = $validated['proof']->store('proofs', 'public');
                        $validated['proof'] = $proofPath;
                  }
                  $validated['name'] = $booking['name'];
                  $validated['email'] = $booking['email'];
                  $validated['phone_number'] = $booking['phone_number'];
                  $validated['total_participant'] = $booking['total_participant'];
                  $validated['started_at'] = $booking['started_at'];
                  $validated['total_amount'] = $booking['total_amount'];
                  $validated['ticket_id'] = $booking['ticket_id'];
                  $validated['is_paid'] = false;
                  $validated['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();

                  $newBooking = $this->bookingRepository->createBooking($validated);

                  $bookingTransactionId = $newBooking->id;


                  SendBookingConfirmedEmail::dispatch($newBooking);
            });

            return $bookingTransactionId;
      }
}