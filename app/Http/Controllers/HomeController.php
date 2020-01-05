<?php

namespace App\Http\Controllers;

use Canvas\Post;
use Canvas\Tag;
use Canvas\Topic;
use Canvas\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        $userMeta = UserMeta::forCurrentUser()->first();
        $emailHash = md5(trim(Str::lower(optional(auth()->user())->email)));

        $data = [
            'authAvatar' => optional($userMeta)->avatar ?? "https://secure.gravatar.com/avatar/{$emailHash}?s=500",
            'tags'       => Tag::all(['name', 'slug']),
            'topics'     => Topic::all(['name', 'slug']),
            'posts'      => Post::published()->withUserMeta()->orderByDesc('published_at')->simplePaginate(10),
        ];

        return view('home', compact('data'));
    }
}
