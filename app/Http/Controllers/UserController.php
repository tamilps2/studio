<?php

namespace App\Http\Controllers;

use App\User;
use Canvas\Post;
use Canvas\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, string $username)
    {
        $userMeta = UserMeta::where('username', $username)->first();

        if ($userMeta) {
            $user = User::where('id', $userMeta->user_id)->first();
            $emailHash = md5(trim(Str::lower(optional(auth()->user())->email)));
            $userAvatar = optional($userMeta)->avatar ?? "https://secure.gravatar.com/avatar/{$emailHash}?s=500";

            $posts = Post::where('user_id', $user->id)
                         ->published()
                         ->orderByDesc('published_at')
                         ->simplePaginate(10);

            $data = [
                'authAvatar' => $userAvatar,
                'avatar'     => $userAvatar,
                'user'       => $user,
                'summary'    => $userMeta->summary,
                'posts'      => $posts,
            ];

            return view('user', compact('data'));
        } else {
            abort(404);
        }
    }
}
