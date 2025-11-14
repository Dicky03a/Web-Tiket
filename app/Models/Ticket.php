<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'thumbnail', 'address', 'path_video', 'price', 'is_popular', 'about', 'open_time_at', 'close_time_at', 'category_id', 'narahubung_id', 'slug'];

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
}
