<?php

namespace App\Http\Controllers;

use App\Mail\VerficationMail;
use App\Models\Category;
use App\Models\CategoryReceipe;
use App\Models\Favourite;
use App\Models\Receipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PageController extends Controller
{
    public function index()
    {
        $receipe = Receipe::latest()->with('favourite')->paginate('6');
        return Inertia::render('Index', ["receipe" => $receipe]);
    }

    // searh receipe on index
    public function indexSearch($keyword)
    {
        $receipe = Receipe::latest()->where("name", "like", "%$keyword%")->with('favourite')->paginate('6');
        return Inertia::render('Index', ["receipe" => $receipe, "keyword" => $keyword]);
    }

    // view several receipe by category
    public function viewReceipeByCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $cat_id = $category->id;

        $receipe = Receipe::with('category')->get();

        $cat_data = [];
        foreach ($receipe as $r) {
            array_push($cat_data, $r['category']);
        }
        $r_data = [];
        foreach ($cat_data as $cat) {
            foreach ($cat as $c) {
                if ($c['pivot']['category_id'] == $cat_id) {
                    array_push($r_data, $c['pivot']['receipe_id']);
                }
            }
        }

        $finalReceipe = Receipe::whereIn('id', $r_data)->latest()->with('favourite')->paginate('6');

        return Inertia::render('Index', ["receipe" => $finalReceipe]);
    }

    // view receipe by author
    public function viewReceipeByAuthor($id)
    {
        $receipe = Receipe::latest()->with('favourite')->where('user_id', $id)->paginate('6');
        return Inertia::render('Index', ["receipe" => $receipe]);
    }

    // veiw favourite receipe
    public function viewFvourtieReceipe()
    {
        $user_id = Auth::user()->id;
        $favourite = Favourite::where('user_id', $user_id)->get();

        $rec_ids = [];
        foreach ($favourite as $f) {
            array_push($rec_ids, $f['receipe_id']);
        }

        $receipe = Receipe::whereIn('id', $rec_ids)->latest()->with('favourite')->paginate('6');

        return Inertia::render('Index', ["receipe" => $receipe]);
    }

    // like a receipe
    public function indexLike($id)
    {
        $checkFav = Favourite::where("user_id", Auth::user()->id)
            ->where("receipe_id", $id)
            ->first();
        if (!$checkFav) {
            Favourite::create([
                "user_id" => Auth::user()->id,
                "receipe_id" => $id,
            ]);
        } else {
            $checkFav->delete();
        }

        return redirect()->back();
    }

    // view receipe
    public function veiwReceipe($slug)
    {
        $receipe = Receipe::where('slug', $slug)->with('step', 'user', 'category')->first();

        return Inertia::render('Receipe', ['receipe' => $receipe]);
    }

    public function category()
    {

        $category = Category::latest()->with('receipe')->withCount('receipe')->paginate('7');

        return Inertia::render('Category', ["category" => $category]);
    }

    // showing category with date
    public function categoryByDate($start_date, $end_date)
    {

        $category = Category::latest()
            ->with('receipe')
            ->withCount('receipe')
            ->whereBetween("created_at", [
                $start_date . " 00:00:00", $end_date . " 23:59:59"
            ])
            ->paginate('7');

        return Inertia::render('Category', [
            "category" => $category,
            "start_date" => $start_date,
            "end_date" => $end_date
        ]);
    }

    // search category by name
    public function categorySearch($keyword)
    {
        $category = Category::latest()
            ->with('receipe')
            ->withCount('receipe')
            ->where('name', "like", "%$keyword%")
            ->paginate('7');

        return Inertia::render('Category', [
            "category" => $category,
        ]);
    }

    // search category by name and date
    public function categorySearchByDate($start_date, $end_date, $keyword)
    {

        $category = Category::latest()
            ->with('receipe')
            ->withCount('receipe')
            ->where('name', "like", "%$keyword%")
            ->whereBetween("created_at", [
                $start_date . " 00:00:00", $end_date . " 23:59:59"
            ])
            ->paginate('7');

        return Inertia::render('Category', [
            "category" => $category,
            "start_date" => $start_date,
            "end_date" => $end_date
        ]);
    }

    // profile
    public function profile()
    {
        return Inertia::render("Profile");
    }

    // edit profile
    public function editProfile()
    {
        return Inertia::render("EditProfile");
    }

    // post edit profile
    public function postEditProfile(Request $request)
    {
        if ($request->name == Auth::user()->name) {
            $request->validate([
                "name" => "required|min:5|max:30|alpha_dash",
            ]);
        } else {
            $request->validate([
                "name" => "required|min:5|max:30|alpha_dash|unique:users,name",
            ]);
        }

        if ($request->image) {
            $request->validate([
                "image" => "image"
            ], ["image.image" => "The file must be an image"]);
        }

        // After passing validation
        if ($request->image) {

            // delete the old profile image if it is not the default icon image
            if (Auth::user()->image !== '/image/delight_icon.png') {
                if (File::exists(public_path(Auth::user()->image))) {
                    File::delete(public_path(Auth::user()->image));
                }
            }

            // upload the file
            $file = $request->file("image");
            $image_name = uniqid() .  $file->getClientOriginalName();
            $image_path = "/image/" . $image_name;
            $file->move(public_path('/image/'), $image_name);
        } else {
            $image_path = Auth::user()->image;
        }

        $user = User::where('id', Auth::user()->id)
            ->update([
                "name" => $request->name,
                "image" => $image_path,
            ]);

        if ($user) {
            return redirect("/profile")->with("success", "Profile updated!");
        }
    }

    // change password
    public function changePassword()
    {
        return Inertia::render("ChangePassword");
    }

    // post change password
    public function postChangePassword(Request $request)
    {
        $request->validate([
            "cur_password" => "required",
            "new_password" => [
                "required",
                'string',
                'min:8',
                'required_with:confirm_new_password',
                'same:confirm_new_password',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            "confirm_new_password" => "required|min:8",
        ]);

        if (!Hash::check($request->cur_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(["cur_password" => "Wrong current password!"]);
        }

        if (Hash::check($request->new_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(["new_password" => "Please type a new password!"]);
        }

        // After passing validation

        $user = User::where('id', Auth::user()->id)
            ->update([
                "password" => Hash::make($request->new_password),
            ]);

        if ($user) {
            return redirect("/profile")->with("success", "Password Changed!");
        } else {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }

    // change email of the logged in user
    public function changeEmail()
    {
        return Inertia::render("ChangeEmail");
    }

    // post change email
    public function postChangeEmail(Request $request)
    {
        if ($request->email && $request->email  ==  Auth::user()->email) {
            return redirect()->back()->withErrors(["email" => "Please type a new email!"]);
        }

        $request->validate([
            "email" => "required|unique:users,email",
        ]);

        // After passing validation
        $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $vercode = substr(str_shuffle($str), 0, 6);

        User::where('id', Auth::user()->id)
            ->update([
                'vercode' => $vercode,
            ]);

        $data = [
            'vercode' => $vercode,
        ];

        Mail::to($request->email)->send(new VerficationMail($data));

        return Inertia::render("ChangeEmailConfirm", ["email" => $request->email]);
    }

    // verify code of change email
    public function postVerifyChangeEmail(Request $request)
    {
        if (!$request->code || !$request->email) {
            return redirect()->back()->withErrors(["code" => "Email verification failed! Please try sending the code again!"]);
        }

        // after passing validation

        if ($request->code ==  Auth::user()->vercode) {
            User::where('id', Auth::user()->id)
                ->update([
                    "email" => $request->email,
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            return redirect("/profile")->with("success", "Email has been changed!");
        } else {
            return redirect()->back()->withErrors(["code" => "Email verification failed! Please try sending the code again!"]);
        }
    }
}
