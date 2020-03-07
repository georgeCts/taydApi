<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $table = 'users_documents';
    protected $fillable =  [
        'user_id',
        'name',
        'file_name',
        'file_url'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
