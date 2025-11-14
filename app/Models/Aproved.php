<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aproved extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'narahubung_id',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'status' => 'string',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function narahubung(): BelongsTo
    {
        return $this->belongsTo(Narahubung::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public static function getLatestApprovalForTicket($ticketId): ?Aproved
    {
        return static::where('ticket_id', $ticketId)
                    ->orderBy('created_at', 'desc')
                    ->first();
    }
}