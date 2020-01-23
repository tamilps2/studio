<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Canvas\Post;
use Canvas\UserMeta;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Find a user for a given username.
     *
     * @param Request $request
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $username)
    {
        $userMeta = UserMeta::where('username', $username)->first();

        if ($userMeta) {
            $user = User::where('id', $userMeta->user_id)->first();
            $posts = Post::where('user_id', $user->id)
                         ->published()
                         ->withUserMeta()
                         ->orderByDesc('published_at')
                         ->get();

            $posts->each->append('read_time');

            $avatar = !empty($userMeta->avatar) ? $userMeta->avatar : generateDefaultGravatar($user->email, 500);

            return response()->json([
                'user'    => $user,
                'avatar'  => $avatar,
                'summary' => $userMeta->summary,
                'posts'   => $posts,
            ]);
        } else {
            return response()->json(null, 404);
        }
    }
}
