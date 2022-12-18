<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\LogLogin;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|min:3|unique:users,name|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:4|max:255',
        ]);

        $slug = Str::random(5);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'slug' => $slug,
            'password' => bcrypt($fields['password'])
        ]);

        LogLogin::create([
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'system' => $request->server('HTTP_USER_AGENT')
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message' => 'success register',
            'user' => ['id' => $user->id, 'name' => $user->name, 'type' => $user->type, 'token' => $token],
        ];

        return response($response);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:4|max:255',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => 'اطلاعات صحیح وارد کنید!'
            ], 422);
        }

        if($user->status == 'deactive')
        {
            return response([
                'message' => 'اکانت شما مسدود شده است! با پشتیبانی تماس بگیرید.'
            ], 422);
        }

        LogLogin::create([
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'system' => $request->server('HTTP_USER_AGENT')
        ]);


        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message' => 'success login',
            'user' => ['id' => $user->id, 'name' => $user->name, 'type' => $user->type, 'token' => $token],
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function me()
    {
        $user = ['name' => auth()->user()->name, 'type' => auth()->user()->type];
        return response($user);

    }

    public function checkAdmin()
    {
        // return auth()->user()->type == 'admin' ? ['a' => 'aa'] : ['b' => 'bb'];
        return auth()->user()->type == 'admin' ? true : false;
    }
}
