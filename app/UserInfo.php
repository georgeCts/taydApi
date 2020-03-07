<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserInfo extends Model
{
    protected $table = 'users_info';
    /* protected $fillable =  [
        'id',
        'name',
        'display_name',
        'description',
        'is_delete'
    ]; */

    public function user(){
        return $this->belongsTo(User::class);
    }
}
