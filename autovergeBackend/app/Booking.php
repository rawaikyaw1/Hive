<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'date', 'customer_id', 'duration', 'note', 'amount'
    ];


    public function customer()
    {
        return $this->belongsTo('App\User', 'customer_id');
    }

    public function bookService()
    {
        return $this->hasMany('App\BookingService', 'booking_id');
    }

}
