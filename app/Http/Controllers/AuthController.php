<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    use  HasApiTokens;

    public function register(Request $request){

        $validator = Validator::make($request->all() , [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);
        if($validator->fails()) {
               return $this->sendError('please check fields' , $validator->errors());
        }

        $data= $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $success['token'] = $user->createToken("myapp")->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse( $success,'user registered successfully');






    }

    public function login(Request $request){

        if (Auth::attempt(['email' => $request->email , 'password' => $request->password])) {

            $user = Auth::user();
            $user = User::find($user->id);
            $success['token'] =  $user->createToken('MyApp')->accessToken;

            $success['name'] =  $user->name;
            return $this->sendResponse($success , 'login with success');

        }

            else{
                return $this->sendError('login failed' , ['error'=> 'Unauthorised']);
            }
        }
    }
