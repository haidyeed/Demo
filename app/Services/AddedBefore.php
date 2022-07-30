<?php
namespace App\Services;
use App\Models\Baby;

class AddedBefore
{

    public static function addedBefore($baby)
    {

        if(Baby::where('name', $baby['name'] )->where('age',$baby['age'])->where('user_id',auth()->user()->id)->exists()){
            return 0;
         }

        if(Baby::where('name', $baby['name'] )->where('age',$baby['age'])->where('user_id',auth()->user()->partner)->exists()){
            return 1;
         }
            return 2;
    }

}
