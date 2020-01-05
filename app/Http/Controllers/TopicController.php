<?php

namespace App\Http\Controllers;

use Canvas\Post;
use Canvas\Tag;
use Canvas\Topic;
use Canvas\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, string $slug)
    {
        $topic = Topic::where('slug', $slug)->first();

        if ($topic) {
            $userMeta = UserMeta::forCurrentUser()->first();
            $emailHash = md5(trim(Str::lower(optional(auth()->user())->email)));
            $userAvatar = optional($userMeta)->avatar ?? "https://secure.gravatar.com/avatar/{$emailHash}?s=500";

            $posts = Post::whereHas('topic', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->published()->orderByDesc('published_at')->simplePaginate(10);

            $data = [
                'authAvatar' => $userAvatar,
                'topic'      => $topic,
                'tags'       => Tag::all(['name', 'slug']),
                'topics'     => Topic::all(['name', 'slug']),
                'posts'      => $posts,
            ];

            return view('topic', compact('data'));
        } else {
            abort(404);
        }
    }
}
