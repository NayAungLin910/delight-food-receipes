<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Receipe;
use App\Models\Step;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Illuminate\Support\Str;
use stdClass;

class AdminPageController extends Controller
{
    public function createCategory()
    {
        return Inertia::render("Admin/CreateCategory");
    }

    // create category
    public function postCreateCategory(Request $request)
    {
        $request->validate([
            "name" => "required",
        ]);

        $slug = uniqid() . Str::slug($request->name);

        Category::create([
            "name" => $request->name,
            "slug" => $slug,
        ]);

        return redirect()->back()->with('success', $request->name . " is created!");
    }

    // delete category
    public function deleteCategory($slug)
    {

        Category::where('slug', $slug)->delete();

        return redirect()->back()->with("success", "Category was deleted!");
    }

    // edit category
    public function editCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();

        return Inertia::render("Admin/EditCategory", ["category" => $category]);
    }

    // post edit category 
    public function postEditCategory(Request $request)
    {
        $request->validate([
            "name" => "required"
        ]);

        $slug = $request->slug;
        $new_name = $request->name;

        $new_slug = uniqid() . Str::slug($new_name);
        Category::where("slug", $slug)
            ->update([
                "name" => $new_name,
                "slug" => $new_slug,
            ]);

        return redirect("/category")->with("success", "Edited Successfully!");
    }

    // create receipe
    public function createReceipe()
    {
        return Inertia::render('Admin/CreateReceipe');
    }

    // post create receipe
    public function postCreateReceipe(Request $request)
    {

        $request->validate([
            "title" => "required",
            "description" => "required",
            "categories" => "required",
            "steps.*.name" => "required",
            "steps.*.desc" => "required",
        ], [
            "steps.*.name.required" => "Please write the title!",
            "steps.*.desc.required" => "Please write the description!",
        ]);

        if ($request->mainImage) {
            $request->validate([
                "mainImage" => "image",
            ], ["mainImage.image" => "The file must be an image"]);
        }

        $count = 0;
        foreach ($request->steps as $step => $step_value) {

            if ($step_value['image'] !== null) {
                $request->validate([
                    "steps.$count.image" => "image"
                ]);
            }
            $count++;
        }

        // After passing the validation
        $cat_ids = [];
        foreach ($request->categories as $category => $category_data) {
            array_push($cat_ids, $category_data['id']);
        }

        // Creating receipe
        if ($request->mainImage) {
            $file = $request->file("mainImage");
            $image_name = uniqid() . $file->getClientOriginalName();
            $mainImage_path = "/image/" . $image_name;
            $file->move(public_path('/image/'), $image_name);
        } else {
            $mainImage_path = "";
        }

        $slug = uniqid() . Str::slug($request->title);

        $receipe = Receipe::create([
            'user_id' => Auth::user()->id,
            'slug' => $slug,
            'name' => $request->title,
            'description' => $request->description,
            'image' => $mainImage_path,
        ]);

        $receipe->category()->attach($cat_ids);
        
        $paths = [];

        for ($i = 0; $i < count($request->steps); $i++) {
            if ($request->steps[$i]['image']) {
                $file = $request->file("steps")[$i]['image'];
                $image_name = uniqid() . $file->getClientOriginalName();
                $image_path = "/image/" . $image_name;
                $file->move(public_path('/image/'), $image_name);

                array_push($paths, $image_path);
            } else {
                array_push($paths, "");
            }
        }

        $count = 0;

        foreach ($request->steps as $step => $step_value) {
            $count++;
            Step::create([
                'receipe_id' => $receipe->id,
                'slug' => uniqid() . Str::slug($step_value['name']),
                'name' => $step_value['name'],
                'description' => $step_value['desc'],
                'image' => $paths[$count - 1],
            ]);
        }

        return redirect()->back()->with("success", "Receipe created!");
    }

    // edit receipe
    public function editReceipe($slug)
    {
        $receipe = Receipe::where('slug', $slug)->with('category')->first();

        $steps = Step::where('receipe_id', $receipe->id)->get();

        $step_count = $steps->count();

        $changeImage = [];
        for ($i = 0; $i < $step_count; $i++) {
            $object = new stdClass();
            $object->image = "";
            array_push($changeImage, $object);
        }

        return Inertia::render('Admin/EditReceipe', ['receipe' => $receipe, 'steps' => $steps, 'changeImage' => $changeImage]);
    }

    // post edit receipe
    public function postEditReceipe(Request $request)
    {
        $request->validate([
            "title" => "required",
            "description" => "required",
            "categories" => "required",
            "steps.*.name" => "required",
            "steps.*.description" => "required",
            "new_steps.*.name" => "required",
            "new_steps.*.desc" => "required",
        ], [
            "steps.*.name.required" => "Please write the title!",
            "steps.*.description.required" => "Please write the description!",
            "new_steps.*.name.required" => "Please write the title!",
            "new_steps.*.desc.required" => "Please write the description!",
        ]);

        if ($request->changeMainImage) {
            $request->validate([
                "changeMainImage" => "image",
            ], ["changeMainImage.image" => "The file must be an image"]);
        }

        $count = 0;

        if ($request->new_steps) {
            foreach ($request->new_steps as $new_step => $new_step_value) {

                if ($new_step_value['image'] !== null) {
                    $request->validate([
                        "new_steps.$count.image" => "image"
                    ], ["new_steps.$count.image.image" => "The file must be an image!"]);
                }
                $count++;
            }
        }

        $count = 0;

        foreach ($request->changeImage as $image => $image_value) {
            if ($image_value['image'] !== null) {
                $request->validate([
                    "changeImage.$count.image" => "image",
                ], ["changeImage.*.image.image" => "The file must be an image!"]);
            }
            $count++;
        }

        // After passing all the validation
        $receipe = Receipe::where('slug', $request->slug)->first();

        // deleting previous image
        if ($receipe->image) {
            if ($request->changeMainImage) {
                if (File::exists(public_path($receipe->image))) {
                    File::delete(public_path($receipe->image));
                }
            }
        }

        // updating previous image
        if ($request->changeMainImage) {
            $file = $request->file("changeMainImage");
            $image_name = uniqid() . $file->getClientOriginalName();
            $changeMainImage_path = "/image/" . $image_name;
            $file->move(public_path('/image/'), $image_name);
        } else {
            if ($receipe->image) {
                $changeMainImage_path = $receipe->image;
            } else {
                $changeMainImage_path = "";
            }
        }

        $slug = uniqid() . Str::slug($request->title);

        // update receipe
        $receipe->name = $request->title;
        $receipe->description = $request->description;
        $receipe->slug = $slug;
        $receipe->image = $changeMainImage_path;
        $receipe->save();

        // deatching the previous categories
        $receipe->category()->detach();

        $cat_ids = [];
        foreach ($request->categories as $category => $category_data) {
            array_push($cat_ids, $category_data['id']);
        }

        // attaching the new categories
        $receipe->category()->attach($cat_ids);



        // handeling file requests of steps
        $paths = [];

        for ($i = 0; $i < count($request->steps); $i++) {
            if ($request->changeImage[$i]['image']) {
                if (File::exists(public_path($request->steps[$i]['image']))) {
                    File::delete(public_path($request->steps[$i]['image']));
                }

                $file = $request->file("changeImage")[$i]['image'];
                $image_name = uniqid() . $file->getClientOriginalName();
                $image_path = "/image/" . $image_name;
                $file->move(public_path('/image/'), $image_name);

                array_push($paths, $image_path);
            } else {
                if ($request->steps[$i]['image']) {
                    array_push($paths, $request->steps[$i]['image']);
                } else {
                    array_push($paths, "");
                }
            }
        }

        // update the steps
        for ($i = 0; $i < count($request->steps); $i++) {
            $slug = uniqid() . Str::slug($request->steps[$i]['name']);
            Step::where('slug', $request->steps[$i]['slug'])
                ->update([
                    'slug' => $slug,
                    'description' => $request->steps[$i]['description'],
                    'image' => $paths[$i],
                ]);
        }



        // handeling images for new steps
        $new_paths = [];

        if ($request->new_steps) {
            for ($i = 0; $i < count($request->new_steps); $i++) {
                if ($request->new_steps[$i]['image']) {
                    $file = $request->file("new_steps")[$i]["image"];
                    $image_name = uniqid() . $file->getClientOriginalName();
                    $image_path = "/image/" . $image_name;
                    $file->move(public_path('/image/'), $image_name);
                    array_push($new_paths, $image_path);
                } else {
                    array_push($new_paths, "");
                }
            }
        }

        // create new steps

        $count = 0;

        if ($request->new_steps) {
            foreach ($request->new_steps as $new_step => $new_step_value) {
                $count++;
                Step::create([
                    'receipe_id' => $receipe->id,
                    'slug' => uniqid() . Str::slug($new_step_value['name']),
                    'name' => $new_step_value['name'],
                    'description' => $new_step_value['desc'],
                    'image' => $new_paths[$count - 1],
                ]);
            }
        }


        return redirect("view/receipe/" . $receipe->slug)->with("success", "Receipe edited!");
    }

    // delete step
    public function deleteStep($slug)
    {
        $step = Step::where('slug', $slug)->first();

        if ($step->image !== null && $step->image !== "") {
            if (File::exists(public_path($step->image))) {
                File::delete(public_path($step->image));
            }
        }

        $step->delete();

        return redirect()->back()->with("info", "Step deleted!");
    }

    // load delete receipe page
    public function deleteReceipe($slug)
    {
        $receipe = Receipe::where('slug', $slug)->first();

        return Inertia::render("Admin/ConfirmDeleteReceipe", ["receipe" => $receipe]);
    }

    // delete receipe 
    public function postDeleteReceipe(Request $request)
    {
        $receipe = Receipe::where('slug', $request->receipe_slug)->first();
        $receipe_id = $receipe->id;
        $steps = Step::where('receipe_id', $receipe->id)->get();


        // Delete the images
        if ($receipe->image !== "" && $receipe->image !== null) {
            if (File::exists(public_path($receipe->image))) {
                File::delete(public_path($receipe->image));
            }
        }

        for ($i = 0; $i < count($steps); $i++) {
            if ($steps[$i]['image'] !== "" && $steps[$i]["image"] !== null) {
                if (File::exists(public_path($steps[$i]['image']))) {
                    File::delete(public_path($steps[$i]['image']));
                }
            }
        }

        // detach the category relationship
        $receipe->category()->detach();

        // delete the receipe and its steps
        Receipe::where("slug", $request->receipe_slug)->delete();
        Step::where("receipe_id", $receipe_id)->delete();

        return redirect('/')->with("info", "Receipe deleted!");
    }

    // view own receipe
    public function viewOwnReceipe()
    {
        $user_id = Auth::user()->id;
        $receipe = Receipe::where("user_id", $user_id)->latest()->with('favourite')->paginate('6');

        return Inertia::render("Index", ["receipe" => $receipe]);
    }

    // search user
    public function searchUser($keyword)
    {
        $cur_user_id = Auth::user()->id;
        $searched_users = User::where('email', $keyword)->whereNotIn('id', [$cur_user_id])->with('receipe')->first();

        if ($searched_users) {
            return Inertia::render("Profile", ["searched_users" => $searched_users, "search" => $keyword]);
        } else {
            return redirect()->back()->with("error", "Sorry, user not fond!");
        }
    }

    // view searched user receipe
    public function viewUserReceipe($id)
    {
        $user = User::where('id', $id)->first();
        $receipe = Receipe::latest()->with('favourite')->where('user_id', $user->id)->paginate('6');

        return Inertia::render('Index', ["receipe" => $receipe]);
    }

    // make moderator
    public function makeModerator($id)
    {
        $user = User::where('id', $id)->first();
        $user->role = '1';
        $user->save();

        return redirect()->back();
    }

    // make user
    public function makeUser($id){
        $user = User::where('id', $id)->first();
        $user->role = '0';
        $user->save();

        return redirect()->back();
    }

    
}
