<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait LoginTrait
{
    public function login(Request $request)
    {
        //Incoming Request Validation
        $validator = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
            "password" => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "validation error",
                "message" => $validator->errors()
            ], 422);
        }

        //Attempt Login
        try {
            if (Auth::attempt([
                "email" => $request->email,
                "password" => $request->password
            ])) {
                $user = User::where('email', $request->email)->first();
                return response()->json([
                    "message" => "login Successful",
                    "token" => $user->createToken($user->name, [$user->type])->plainTextToken
                ], 200);
            } else {
                return response()->json([
                    "message" => "incorrect credentials!"
                ], 422);
            }
        } catch (\Throwable $th) {
            // Log the exception details here
            return response()->json([
                "message" => $th,
                // "message" => "Internal Server Error',

            ], 500);
        }
    }

    public static function logout(Request $request)
    {

        try {
            //get a hold on user's current access token and delete that
            $request->user()->currentAccessToken()->delete();

            return response()->json(
                [
                    "message" => "logged out",
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "message" => $th,
                ],
                500
            );
        }
    }
}
