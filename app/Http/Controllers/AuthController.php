<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    use AuthorizesRequests,DispatchesJobs,validatesRequests;

    public function register(Request $request)
    {

        $validateData= $request->validate([
            'first_name'=> 'required|string',
            'last_name'=> 'required|string',
            'password' => 'required|string',
             'email'=> 'required|email|unique:users,email',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        $user = new User();

            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            if ($request->hasFile('image')) {
                $user->image = $request->file('image')->store('image/users_images', 'public');
            }
            $user->save();
            $token = $user->createToken($user->first_name . ' ' . $user->last_name . "-AuthToken")->plainTextToken;
        $Data=[
            "id"=>$user->id,
            "first_name"=>$user->first_name,
            "last_name"=>$user->last_name,
            "email"=>$user->email,
        ];
        return response()->json(["message" =>"user created successfully","user"=>$Data,"token"=>$token],200);
        }

        public function login(Request $request){
            $validateData= $request->validate([
                'password' => 'required|string',
                 'email'=> 'required|email',
            ]);
            $user=User::where("email",$request->email)->first();
            if(!$user || !Hash::check($request->password,$user->password,)){
                return response()->json(["message" =>"invaliad credentails"],401);
            }
            $token=$user->createToken($user->first_name . ' ' . $user->last_name . "-AuthToken")->plainTextToken;
            $Data=[
                "id"=>$user->id,
                "first_name"=>$user->first_name,
                "last_name"=>$user->last_name,
                "email"=>$user->email,

            ];
            return response()->json(["message" =>"user logined successfully","user"=>$Data,"token"=>$token],200);
        }
        public function logout(Request $request){
                auth()->user()->tokens()->delete();
                return response()->json(["message" => "User logout successfully"]);


        }

}
