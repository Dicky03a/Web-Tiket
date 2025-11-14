<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;


class TicketRepository implements TicketRepositoryInterface
{
    public function getPopularTickets($limit = 4)
    {
        // Ambil approval terbaru untuk setiap tiket
        $latestApprovals = \App\Models\Aproved::selectRaw('ticket_id, status, MAX(id) as latest_approval_id')
                                  ->groupBy('ticket_id', 'status');

        // Ambil tiket-tiket yang memiliki approval terbaru dengan status 'approved'
        return Ticket::where('is_popular', true)
                     ->joinSub($latestApprovals, 'latest_approvals', function ($join) {
                         $join->on('tickets.id', '=', 'latest_approvals.ticket_id');
                     })
                     ->where('latest_approvals.status', 'approved')
                     ->where('latest_approvals.latest_approval_id', function ($subQuery) {
                         $subQuery->selectRaw('MAX(id)')
                                  ->from('aproveds')
                                  ->whereColumn('aproveds.ticket_id', 'tickets.id');
                     })
                     ->take($limit)
                     ->get();
    }

    public function getAllNewTickets()
    {
        // Ambil approval terbaru untuk setiap tiket
        $latestApprovals = \App\Models\Aproved::selectRaw('ticket_id, status, MAX(id) as latest_approval_id')
                                  ->groupBy('ticket_id', 'status');

        // Ambil tiket-tiket yang memiliki approval terbaru dengan status 'approved'
        return Ticket::joinSub($latestApprovals, 'latest_approvals', function ($join) {
                         $join->on('tickets.id', '=', 'latest_approvals.ticket_id');
                     })
                     ->where('latest_approvals.status', 'approved')
                     ->where('latest_approvals.latest_approval_id', function ($subQuery) {
                         $subQuery->selectRaw('MAX(id)')
                                  ->from('aproveds')
                                  ->whereColumn('aproveds.ticket_id', 'tickets.id');
                     })
                     ->latest()
                     ->get();
    }

    public function find($id)
    {
        // Ambil approval terbaru untuk tiket tertentu
        $latestApproval = \App\Models\Aproved::where('ticket_id', $id)
                                  ->latest()
                                  ->first();

        // Hanya kembalikan tiket jika approval terbaru berstatus 'approved'
        if ($latestApproval && $latestApproval->status === 'approved') {
            return Ticket::find($id);
        }

        return null;
    }

    public function getPrice($ticketId)
    {
        $ticket = $this->find($ticketId);
        return $ticket ? $ticket->price : 0;
    }
}