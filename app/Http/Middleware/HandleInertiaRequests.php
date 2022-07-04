<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Favourite;
use App\Models\Receipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'success' => fn() => $request->session()->get('success'),
            'info' => fn() => $request->session()->get('info'),
            'error' => fn() => $request->session()->get('error'),
            'auth' => fn() => Auth::user() ? Auth::user() : null,
            'category' => fn() => Category::with('receipe')->orderBy('name', 'asc')->get(),
            'favourite' => fn() =>Auth::user() ?  Favourite::where('user_id', Auth::user()->id)->with('receipe')->get() : null,
            'my_receipe' => fn() =>Auth::user() ? Receipe::where('user_id', Auth::user()->id)->get() : null, 
        ]);
    }
}
