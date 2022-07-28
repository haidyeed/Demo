<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Baby extends Authenticatable
{
    use HasApiTokens, HasFactory, HasApiTokens;
    protected $table='babies';

    protected $fillable = [
        'name', 'age' , 'user_id'
    ];

    public function parent()
    {
        return $this->belongsTo(User::class,'user_id');
    }


}
