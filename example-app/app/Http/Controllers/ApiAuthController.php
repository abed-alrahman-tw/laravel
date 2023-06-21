<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class ApiAuthController extends Controller
{
    //

    public function login(Request $request){

        $validator = Validator($request->all(), [
            "email" => 'email|required|exists:admins,email',
            "password" => 'required|string'

        ]);

        if (!$validator->fails()) {
            $email = $request->input('email');
            $password = $request->input('password');

            $admin = Admin::where('email', '=', $email)->first();

            if (Hash::check($password, $admin->password)) {

                $token = $admin->createToken('API-Token-admin' . $admin->id);
                $admin->token = $token->accessToken;

                return response()->json(['status' => true, 'message' => "Logged in successfully", 'data' => $admin], Response::HTTP_OK);
            }else {

                return response()->json(['status'=>false  ,'message' => 'Please re-check yor email or password'], status:Response::HTTP_BAD_REQUEST);
            }
        }else {
            return response()->json(['status'=>false  ,'message' => $validator->getMessageBag()->first() ],status:Response::HTTP_BAD_REQUEST);

        }
    }
    public function register(Request $request){

        $validator = Validator($request->all(), [
            'name'=> 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => ['string' ,Password::min(6)->uncompromised()->mixedcase()->numbers()->letters()]

        ] );

        $email = $request->input('email');
        $password = $request->input('password');
        $name = $request->input('name');
        $admin = new Admin();
        $saved = false ; 

        if(!$validator->fails()){

        $admin->email = $email;
        $admin->password = Hash::make($password);
        $admin->name = $name;

        $saved = $admin->save();

        }
            return response()->json(['status'=>$saved , 'message' => $saved ? 
            "registerd successfuly" : $validator->getMessageBag()->first()], 
            $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);

    }

    public function logout(Request $request ){

        $admin = $request->user();
        $revoked = $admin->token()->revoke();
        
        return response()->json(['status' => $revoked , 'message' => $revoked ? "logged out successfully" : "log out failed"]);

    }
}
