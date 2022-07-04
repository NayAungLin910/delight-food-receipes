<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Mail\VerficationMail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MailController extends Controller
{
    public function emailVerify(){
        return Inertia::render("EmailVerify");
    }

    // verify email
    public function postEmailVerify(Request $request){

        $request->validate([
            "vercode" => "required",
        ]);

        $user = Auth::user();

        if($request->vercode ==  $user->vercode){

            DB::table('users')
              ->where('id', $user->id)
              ->update([
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
              ]);

            return redirect("/")->with("success", "Welcome " . $user->name . "!");
        }else{
            return redirect("/email/verify")->withErrors([
                "vercode" => "Sorry the verification code is uncorrect!"
            ]);
        }
    }

    // resend email
    public function resendEmailVerify(){
        $user = Auth::user();
        $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $vercode = substr(str_shuffle($str), 0, 6);

        DB::table('users')
          ->where('id', $user->id)
          ->update([
            'email_verified_at' => null,
            "vercode" => $vercode
          ]);

          $data = [
            "vercode" => $vercode
          ];
        
          Mail::to(Auth::user()->email)->send(new VerficationMail($data));

        return redirect()->back()->with("success", "Verification code had been sent again!");
    }

    // cancel email verificatoin and go back to register

    public function cancelEmailVerify(){
      $user_id = Auth::user()->id;
      if(Auth::user()->image !== '/image/delight_icon.png'){
        if(File::exists(public_path(Auth::user()->image))){
          File::delete(public_path(Auth::user()->image));
        }
      }
      DB::table('users')
        ->where('id', $user_id)
        ->delete();
      Auth::logout();
      return redirect("/register");
    }
}
