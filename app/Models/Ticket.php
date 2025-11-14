<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'thumbnail',
        'address',
        'path_video',
        'price',
        'is_popular',
        'about',
        'open_time_at',
        'close_time_at',
        'category_id',
        'narahubung_id',
        'slug',
        'approval_status',
        'approval_notes'
    ];

    protected $casts = [
        'approval_status' => 'string',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function narahubung()
    {
        return $this->belongsTo(Narahubung::class, 'narahubung_id');
    }

    public function photos()
    {
        return $this->hasMany(TicketPhoto::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Aproved::class, 'ticket_id');
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    // Fungsi untuk mendapatkan status dari sistem approval terpisah
    public function getLatestApprovalStatus(): string
    {
        $latestApproval = $this->approvals()->latest()->first();
        return $latestApproval ? $latestApproval->status : 'pending';
    }

    public function isLatestApprovalApproved(): bool
    {
        return $this->getLatestApprovalStatus() === 'approved';
    }

    public function isLatestApprovalPending(): bool
    {
        return $this->getLatestApprovalStatus() === 'pending';
    }

    public function isLatestApprovalRejected(): bool
    {
        return $this->getLatestApprovalStatus() === 'rejected';
    }

    // Scope untuk hanya mengambil tiket yang memiliki approval 'approved'
    public function scopeApproved($query)
    {
        return $query->whereHas('approvals', function ($subQuery) {
            $subQuery->where('status', 'approved');
        });
    }

    // Scope untuk hanya mengambil tiket yang bisa tampil di frontend (sama dengan approved untuk sekarang)
    public function scopeVisible($query)
    {
        return $query->whereHas('approvals', function ($subQuery) {
            $subQuery->where('status', 'approved');
        });
    }

    // Scope untuk hanya mengambil tiket yang belum diproses atau ditunda
    public function scopePending($query)
    {
        return $query->whereDoesntHave('approvals', function ($subQuery) {
            $subQuery->where('status', 'approved')
                     ->orWhere('status', 'rejected');
        });
    }
}
