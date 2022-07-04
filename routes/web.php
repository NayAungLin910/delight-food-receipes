<?php

use App\Http\Controllers\Admin\AdminPageController;
use App\Mail\TestEmail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Before auth routes
Route::middleware(['AuthNotUser'])->group(function () {
    Route::get('/login', [AuthController::class, "login"]);
    Route::post('/login', [AuthController::class, "postLogin"]);
    Route::get('/register', [AuthController::class, "register"]);
    Route::post('/register', [AuthController::class, "postRegister"]);
    Route::get('/forgot/password', [AuthController::class, "forgotPassword"]);
    Route::post('/forgot/password', [AuthController::class, "postForgotPassword"]);
    Route::get('/forgot/password/verify', [AuthController::class, "forgotPassword"]);
    Route::post('/forgot/password/verify', [AuthController::class, "postVerifyCode"]);
    Route::get('/change/password', [AuthController::class, "forgotPassword"]);
    Route::post('/change/password', [AuthController::class, "changePassword"]);
});

// email verificatoin routes
Route::middleware(['AuthVerifyEmail'])->group(function () {
    Route::get('/email/verify', [MailController::class, "emailVerify"]);
    Route::post('/email/verify', [MailController::class, "postEmailVerify"]);
    Route::get('/email/verify/resend', [MailController::class, "resendEmailVerify"]);
    Route::get('/email/verify/cancel', [MailController::class, "cancelEmailVerify"]);
});

// 
Route::middleware(['AuthNotVerifyEmail'])->group(function () {
    Route::get('/', [PageController::class, "index"]);
    Route::get('/index/search/{keyword}', [PageController::class, "indexSearch"]);
    // view receipe
    Route::get('/view/receipe/{slug}', [PageController::class, 'veiwReceipe']);
    // veiw receipe according to category
    Route::get('/view/category/receipe/{slug}', [PageController::class, 'viewReceipeByCategory']);
    // veiw receipe according to author
    Route::get('/view/author/receipe/{id}', [PageController::class, 'viewReceipeByAuthor']);
    // Category
    Route::get('/category', [PageController::class, "category"]);
    Route::get('/category/{start_date}/{end_date}', [PageController::class, "categoryByDate"]);
    Route::get('/search/category/{keyword}', [PageController::class, 'categorySearch']);
    Route::get('/date/category/search/{start_date}/{end_date}/{keyword}', [PageController::class, 'categorySearchByDate']);
});

// Admin routes
Route::middleware(["AdminRoute"])->group(function () {
    // Category
    Route::get('/create/category', [AdminPageController::class, "createCategory"]);
    Route::post('/create/category', [AdminPageController::class, "postCreateCategory"]);
    Route::get('/delete/category/{slug}', [AdminPageController::class, "deleteCategory"]);
    Route::get('/edit/category/{slug}', [AdminPageController::class, "editCategory"]);
    Route::post('/edit/category/', [AdminPageController::class, "postEditCategory"]);
    // Receipe
    Route::get('/create/receipe', [AdminPageController::class, "createReceipe"]);
    Route::post('/create/receipe', [AdminPageController::class, "postCreateReceipe"]);
    Route::get('/edit/receipe/{slug}', [AdminPageController::class, "editReceipe"]);
    Route::post('/edit/receipe/', [AdminPageController::class, 'postEditReceipe']);
    Route::get('/delete/step/{slug}', [AdminPageController::class, "deleteStep"]);
    Route::get('/delete/receipe/{slug}', [AdminPageController::class, "deleteReceipe"]);
    Route::post('/delete/receipe/', [AdminPageController::class, "postDeleteReceipe"]);
    // view receipe of the logged in admin or mod
    Route::get('/view/own/receipe', [AdminPageController::class, 'viewOwnReceipe']);
});

Route::middleware(['AdminOnlyRoute'])->group(function () {
    Route::get('/user/search/{keyword}', [AdminPageController::class, 'searchUser']);
    Route::get('/view/searchuser/receipe/{id}', [AdminPageController::class, 'viewUserReceipe']);
    // make user moderator
    Route::get('/make/moderator/{id}', [AdminPageController::class, 'makeModerator']);
    Route::get('/make/user/{id}', [AdminPageController::class, 'makeUser']);
});


Route::middleware(['AuthUser'])->group(function () {
    // logout
    Route::get('/logout', [AuthController::class, "logout"]);
    // like receipe 
    Route::get('/index/like/{id}', [PageController::class, 'indexLike']);
    // profile
    Route::get('/profile', [PageController::class, 'profile']);
    // view favourite receipe
    Route::get('/view/favourtie/receipe', [PageController::class, 'viewFvourtieReceipe']);
    // edit profile
    Route::get('/edit/profile/', [PageController::class, 'editProfile']);
    Route::post('/edit/profile/', [PageController::class, 'postEditProfile']);
    // change password
    Route::get('/profile/password', [PageController::class, 'changePassword']);
    Route::post('/profile/password', [PageController::class, 'postChangePassword']);
    // change new email
    Route::get('/change/email/', [PageController::class, 'changeEmail']);
    Route::post('/change/email/', [PageController::class, 'postChangeEmail']);
    Route::post('/change/email/verify/', [PageController::class, 'postVerifyChangeEmail']);
});










