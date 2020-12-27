<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    protected $fillable = [
        'booking_id', 'service_id'
    ];

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }
}
