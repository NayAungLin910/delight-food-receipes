<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Mail\VerficationMail;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class AuthController extends Controller
{   
    public function login(){
        return Inertia::render('Login');
    }

    // login
    public function postLogin(Request $request){
        $request->validate([
            "email" => "required",
            "password" => "required",
        ]);

        $cre = $request->only("email", "password");
        if(Auth::attempt($cre, $request->remember)){
            return redirect("/");
        }else{
            return redirect()->back()->withErrors(["errors" => "Email or password do not match!"]);
        }
    }

    public function register(){
        return Inertia::render('Register');
    }

    // register 
    public function postRegister(Request $request){
        $request->validate([
            "name" => "required|min:5|max:30|alpha_dash|unique:users,name",  
            "email" => "required|unique:users,email",
            "password" => [
                "required",
                'string',
                'min:8',
                'required_with:password_confirmation',
                'same:password_confirmation',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            "password_confirmation" => "required|min:8",  
        ]);

        if($request->image){

            $request->validate([
                "image" => "image",
            ], ["image.image" => "The file must be an image"]);

            $file = $request->file("image");
            $image_name = uniqid() .  $file->getClientOriginalName();
            $image_path = "/image/" . $image_name;
            $file->move(public_path('/image/'), $image_name);
        }else{
            $image_path = "/image/" . "delight_icon.png";
        }
        $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "image" => $image_path,
            "vercode" => substr(str_shuffle($str), 0, 6),
        ]);

        $cre = $request->only("email", "password");
        if(Auth::attempt($cre, $request->remember)){
            $data = [
                'vercode' => Auth::user()->vercode,
            ];

            Mail::to($request->email)->send(new VerficationMail($data));
            return redirect("/email/verify");
        }
    }

    // logout 
    public function logout(){
        Auth::logout();
        return redirect("/login")->with("info", "Please login again!");
    }

    // forgot password
    public function forgotPassword(){
        return Inertia::render('ForgotPassword');        
    }

    public function postForgotPassword(Request $request){
        $request->validate([
            "email" => "required",
        ]);

        $curUser = User::where('email', '=', $request->email)->first();

        if($curUser == null){
            return redirect()->back()->withErrors(["email" => "Email has not been reigsterd!"]);
        }else{
            $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $vercode = substr(str_shuffle($str), 0, 6);

            DB::table('users')
              ->where('id', $curUser->id)
              ->update([
                "vercode" => $vercode
            ]);

            $data = [
                "vercode" => $vercode
            ];

            Mail::to($request->email)->send(new VerficationMail($data));

            $data = [
                "vercode" => $vercode,
                "email" => $request->email,
            ];
            return Inertia::render("ForgotPassword", ["data" => $data]);
        }
    }

    public function postVerifyCode(Request $request){

        if($request->code == null){
            return Inertia::render("ForgotPassword", [
                "data" => $request->data, "codeError" => "Please fill up the code field!"
                ]
            );
        }

        $curUser = User::where('email', $request->email)->first();

        if($curUser->vercode == $request->code){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->data,
            ]);
        }else{
            return Inertia::render("ForgotPassword", [
                "data" => $request->data, "codeError" => "Code does not match!",
            ]);
        }

    }

    // change password

    public function changePassword(Request $request){

        $password = $request->password;
        $password_confirm = $request->password_confirm;

        if($password == null){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                'finalError' => "Please fill the password field"
            ]);
        }

        if($password_confirm == null){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                'finalConfirmError' => "Please fill the password confirmation field",
            ]);
        }

        if(strlen($password) < 8){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                "finalError" => "Password need to has at least 8 characters"
            ]);
        }

        if(!preg_match('/[a-z]/', $password)){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                "finalError" => "Password need to contain at least one small letter"
            ]);
        }

        if(!preg_match('/[A-Z]/', $password)){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                "finalError" => "Password need to contain at least one capital letter"
            ]);
        }

        if(!preg_match('/\d/', $password)){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                "finalError" => "Password need to contain at least one number"
            ]);
        }

        if(!preg_match('/[^a-zA-Z\d]/', $password)){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                "finalError" => "Password need to contain at least one special character"
            ]);
        }

        if($password !==  $password_confirm){
            return Inertia::render("ForgotPassword", [
                "finalData" => $request->finalData,
                "finalConfirmError" => "Confirm password need to be the same with new password"
            ]);
        }

        DB::table('users')
              ->where('email', $request->email)
              ->update([
                "password" => Hash::make($request->password)
            ]);

        return redirect("/login")->with("success", "Password has been changed. Please login agian!");
    }
}
