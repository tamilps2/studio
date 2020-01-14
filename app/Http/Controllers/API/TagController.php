<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Canvas\Post;
use Canvas\Tag;
use Canvas\Topic;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Get all of the tags.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(Tag::all(['name', 'slug']));
    }

    /**
     * Get all posts for a given tag.
     *
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostsForTag(Request $request, string $slug)
    {
        $tag = Tag::select('name', 'slug')->where('slug', $slug)->first();

        if ($tag) {
            $posts = Post::whereHas('tags', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->published()->withUserMeta()->orderByDesc('published_at')->get();

            $posts->each->append('read_time');

            return response()->json([
                'tag'    => $tag,
                'posts'  => $posts,
                'tags'   => Tag::select(['name', 'slug'])->whereHas('posts')->get(),
                'topics' => Topic::select(['name', 'slug'])->whereHas('posts')->get(),
            ]);
        } else {
            return response()->json(null, 404);
        }
    }
}
