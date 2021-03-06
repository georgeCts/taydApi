<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Service;

class ChatMessage extends Model
{
    protected $table    = 'chat_messages';
    protected $fillable = [
        'service_id',
        'message',
        'fromTayder'
    ];

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
