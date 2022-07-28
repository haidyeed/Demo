<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Baby;
use Illuminate\Http\Request;

class BabyController extends Controller
{
    public function addBaby(Request $request)
    {
        $baby = $request->only(['name', 'age']);
    //    $x= Baby::where('name', $baby['name'] )->where('age',$baby['age'])->first();
    //      if(1){
    //         return response()->json([
    //             'success' => false,
    //             'message' => $x->parent,
    //         ]);
    //      }


        $validate_data = [
            'name' => 'required|string|min:4',
            'age' => 'required|numeric|min:1|max:8',
        ];

        $validator = Validator::make($baby, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }
 
        $baby = Baby::create([
            'name' => $baby['name'],
            'age' => $baby['age'],
            'user_id'=>auth()->user()->id
        ]);
         
        return response()->json([
            'success' => true,
            'message' => 'a new baby has been added successfully'
        ], 200);
    }




}
