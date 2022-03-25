<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::find(1);
        $permisions = [
            'posts.index',
            'posts.store',
            'posts.show',
            'posts.update',
            'posts.destroy',
            'users.writer.create',
            'users.subscriber.create',
            'users.destroy',
        ];
        $tokenResult = $user->createToken('authToken', $permisions)->plainTextToken;
        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
//        try {
//            $request->validate([
//                'email' => 'email|required',
//                'password' => 'required'
//            ]);
//
//            $credentials = request(['email', 'password']);
//            if (!Auth::attempt($credentials)) {
//                return response()->json([
//                    'status_code' => 500,
//                    'message' => 'Unauthorized'
//                ]);
//            }
//
//            $user = User::where('email', $request->email)->first();
//
//            if (!Hash::check($request->password, $user->password, [])) {
//                throw new \Exception('Error in Login');
//            }
//            $userRole = $user->role;
//            if($userRole == User::ROLE_ADMIN){
//                $permisions = [
//                    'posts.index',
//                    'posts.store',
//                    'posts.show',
//                    'posts.update',
//                    'posts.destroy',
//                    'users.writer.create',
//                    'users.subscriber.create',
//                    'users.destroy',
//                ];
//            }
//            if($userRole == User::ROLE_WRITER) {
//                $permisions = [
//                    'posts.store',
//                    'posts.show',
//                    'posts.update',
//                    'posts.destroy'
//                ];
//            }
//            if($userRole == User::ROLE_SUBSCRIBER) {
//                $permisions = [
//                    'posts.show'
//                ];
//            }
//            $tokenResult = $user->createToken('authToken', $permisions)->plainTextToken;
//
////            $tokenResult = $user->createToken('authToken')->plainTextToken;
//            return response()->json([
//                'status_code' => 200,
//                'access_token' => $tokenResult,
//                'token_type' => 'Bearer',
//            ]);
//        } catch (\Exception $error) {
//            return response()->json([
//                'status_code' => 500,
//                'message' => 'Error in Login',
//                'error' => $error,
//            ]);
//        }
    }
}
