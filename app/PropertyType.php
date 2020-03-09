<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserProperty;

class PropertyType extends Model
{
    protected $table = 'properties_types';
    protected $fillable =  [
        'name',
        'active'
    ];

    public function property(){
        return $this->belongsTo(UserProperty::class);
    }
}
