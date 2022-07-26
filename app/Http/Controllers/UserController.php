<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->only(['name', 'email', 'password']);

        $validate_data = [
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }
 
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password'])
        ]);
         
        return response()->json([
            'success' => true,
            'message' => 'User registered succesfully, Use Login method to receive token.'
        ], 200);
    }



    public function login(Request $request)
    {
        $input = $request->only(['email', 'password']);

        $validate_data = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        // authentication attempt
        if (auth()->attempt($input)) {
            $token = auth()->user()->createToken('passport_token')->accessToken;
            
            return response()->json([
                'success' => true,
                'message' => 'User login succesfully, Use token to authenticate.',
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }


    public function userDetails()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully.',
            'data' => auth()->user()
        ], 200);
    }

    public function logout()
    {
        $access_token = auth()->user()->token();

        // logout from only current device
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($access_token->id);

        // use this method to logout from all devices
        // $refreshTokenRepository = app(RefreshTokenRepository::class);
        // $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($$access_token->id);

        return response()->json([
            'success' => true,
            'message' => 'User logout successfully.'
        ], 200);
    }


    public function addPartner(Request $request)
    {
        $partner = $request->partner;

       if (User::where('id', $partner )->exists() && $partner!=auth()->user()->id ) {

        $user = User::where('id',auth()->user()->id)->first();
            $user->partner=$partner;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'partner added successfully'
            ], 200);       
         }

            else{
                return response()->json([
                    'success' => false,
                    'message' => 'partner not found'
                ], 400);    
            }

    }


    public function showMyPartner()
    {
        $user = User::where('id',auth()->user()->id)->first();
        if($user->partner>0){
            $partner = User::where('id',$user->partner)->first();
            return response()->json([
                'success' => true,
                'data'=>$partner,
                'message' => 'partner found'
            ], 200); 
        }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'no partner found'
                ], 400);    
            }

    }


 
}
