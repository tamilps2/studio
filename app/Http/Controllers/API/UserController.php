<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Canvas\Post;
use Canvas\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function show(Request $request, string $username)
    {
        $userMeta = UserMeta::where('username', $username)->first();

        if ($userMeta) {
            $user = User::where('id', $userMeta->user_id)->first();
            $emailHash = md5(trim(Str::lower(optional(auth()->user())->email)));
            $userAvatar = optional($userMeta)->avatar ?? "https://secure.gravatar.com/avatar/{$emailHash}?s=500";

            $posts = Post::where('user_id', $user->id)
                         ->published()
                         ->orderByDesc('published_at')
                         ->get();

            $posts->each->append('read_time');

            return response()->json([
                'user'       => $user,
                'avatar'     => $userAvatar,
                'summary'    => $userMeta->summary,
                'posts'      => $posts,
            ]);
        } else {
            return response()->json(null, 404);
        }
    }
}
