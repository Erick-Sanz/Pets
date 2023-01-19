<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
{
    public function register(Request $request){
        $fields=$request->validate([
            'name' => 'required|string|min:3|max:50',
            'surname' => 'required|string|min:3|max:50',
            'email' => ['required','string','email','unique:users','regex:/(.*)@(gmail|yahoo|outlook)\.com/i'],
            'password' =>  ['required',Password::defaults()]
        ]);
        $user=User::create([
            'name' => $fields['name'],
            'surname' => $fields['surname'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);
        $token=$user->createToken('api_token')->plainTextToken;
        $response=[
            'user'=>$user,
            'token' =>$token
        ];
        collect($response)->toJson();
        return response($response,201)->header('Content-Type', 'application/json');
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        $response=[
            'message'=>'Logged out'
        ];
        collect($response)->toJson();
        return response($response,200)->header('Content-Type', 'application/json');
    }

    public function login(Request $request){
        $fields=$request->validate([
            'email' => ['required','string','email','regex:/(.*)@(gmail|yahoo|outlook)\.com/i'],
            'password' => 'required|string|min:3|max:50'
        ]);
        $user=User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password)){
            $response=collect(['message'=>'wrong credentials'])->toJson();
            return response($response,401)->header('Content-Type', 'application/json');
        }
        $token=$user->createToken('myapptoken')->plainTextToken;

        $response=[
            'user'=>[
                'id'=>$user['id'],
                'name'=>$user['name'],
                'surtname'=>$user['lastname'],
                'email'=>$user['email'],
                'created_at'=>$user['created_at'],
                'updated_at'=>$user['updated_at']],
            'token' =>$token
        ];
        collect($response)->toJson();
        return response($response,201)->header('Content-Type', 'application/json');
    }
}
