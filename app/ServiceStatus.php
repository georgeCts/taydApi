<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Service;

class ServiceStatus extends Model
{
    protected $table    = 'services_status';
    protected $fillable = [
        'name',
    ];

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
