<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Baby;
use Illuminate\Http\Request;
use App\Services\AddedBefore;

class BabyController extends Controller
{
    public function addBaby(Request $request)
    {
        $baby = $request->only(['name', 'age']);
        //  if(Baby::where('name', $baby['name'] )->where('age',$baby['age'])->where('user_id',auth()->user()->id)->exists()){
            if(AddedBefore::addedBefore($baby)==0){
            return response()->json([
                'success' => false,
                'message' => 'this baby has been added before',
            ]);
         }

        //  if(Baby::where('name', $baby['name'] )->where('age',$baby['age'])->where('user_id',auth()->user()->partner)->exists()){
            if(AddedBefore::addedBefore($baby)==1){
            return response()->json([
                'success' => false,
                'message' => 'this baby has been added by your partner',
            ]);
         }

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


    public function editBaby(Request $request, $id)
    {
         $baby= Baby::find($id);

         if(!$baby){
            return response()->json([
                'success' => false,
                'message' => 'baby not found'
            ], 400);
        }

        elseif($baby->user_id==auth()->user()->id ||$baby->user_id==auth()->user()->partner ){

         $data = $request->only(['name', 'age']);

         $validate_data = [
            'name' => 'string|min:4',
            'age' => 'numeric|min:1|max:8',
        ];

        $validator = Validator::make($data, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $baby->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'your baby has been edited successfully'
        ], 200);
       }
    }


    public function showMyBabies()
    {
        $babies = Baby::where('user_id',auth()->user()->id)->orWhere('user_id',auth()->user()->partner)->select('name','age')->get();
        $babies_number = $babies->count();
        if($babies_number !=0){
            return response()->json([
                'success' => true,
                'data'=>$babies,'babies_number' => $babies->count(),
                'message' => 'babies found'
            ], 200);
        }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'no baby is found'
                ], 400);
            }

    }

    public function showMyBaby($id)
    {
        $baby= Baby::where('id',$id)->select('name','age','user_id')->get();

        if($baby[0]->user_id==auth()->user()->id ||$baby[0]->user_id==auth()->user()->partner ){
            return response()->json([
                'success' => true,
                'data'=>$baby,
                'message' => 'baby found'
            ], 200);
        }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'you are not able to get this info'
                ], 400);
            }

    }


    public function deleteBaby($id)
    {
        $baby = Baby::find($id);
        if(!$baby){
            return response()->json([
                'success' => false,
                'message' => 'baby not found'
            ], 400);
        }

        elseif($baby->user_id==auth()->user()->id){

              $baby->delete();

            return response()->json([
                'success' => true,
                'message' => 'baby deleted successfully'
            ], 200);
        }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'you are not able to delete this baby'
                ], 400);
            }

    }

}
