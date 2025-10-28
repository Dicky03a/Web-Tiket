<?php

namespace App\Services;

use App\Models\Ticket;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;

class BookingService
{
      protected $bookingRepository;
      protected $ticketRepository;

      public function __construct(TicketRepositoryInterface $ticketRepository, BookingRepositoryInterface $bookingRepository)
      {
            $this->ticketRepository = $ticketRepository;
            $this->bookingRepository = $bookingRepository;
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
}