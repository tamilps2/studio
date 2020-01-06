<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Canvas\Post;
use Canvas\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Get all of the tags.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(Topic::all(['name', 'slug']));
    }

    /**
     * Get all posts for a given topic.
     *
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostsForTopic(Request $request, string $slug)
    {
        $topic = Topic::select('name', 'slug')->where('slug', $slug)->first();

        if ($topic) {
            $posts = Post::whereHas('topic', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->published()->withUserMeta()->orderByDesc('published_at')->get();

            return response()->json([
                'topic' => $topic,
                'posts' => $posts,
            ]);
        } else {
            return response()->json(null, 404);
        }
    }
}
