<?php

namespace App\Http\Controllers;

use Canvas\Events\PostViewed;
use Canvas\Post;
use Canvas\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $username
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, string $username, string $slug)
    {
        $meta = UserMeta::where('username', $username)->first();

        if ($meta) {
            $posts = Post::with('tags', 'topic', 'user')->published()->get();
            $post = $posts->firstWhere('slug', $slug);

            if ($post && $post->published && $post->user->id == $meta->user_id) {
                $readNext = $posts->sortBy('published_at')->firstWhere('published_at', '>', $post->published_at);

                if ($readNext) {
                    $randomPool = $posts->filter(function ($item) use ($readNext, $post) {
                        return $item->id != $post->id && $item->id != $readNext->id;
                    });
                } else {
                    $randomPool = $posts->filter(function ($item) use ($readNext, $post) {
                        return $item->id != $post->id;
                    });
                }

                if ($post->tags || $post->topic) {
                    $related = $this->findRelatedViaTaxonomy($randomPool, $post);
                    $readRandom = $related->isEmpty() ? null : $related->first();
                } else {
                    $readRandom = $randomPool->random();
                }

                $metaData = UserMeta::forCurrentUser()->first();
                $emailHash = md5(trim(Str::lower(optional(request()->user())->email)));

                $data = [
                    'avatar' => optional($metaData)->avatar && !empty(optional($metaData)->avatar) ? $metaData->avatar : "https://secure.gravatar.com/avatar/{$emailHash}?s=500",
                    'author' => $post->author,
                    'post'   => $post,
                    'meta'   => $post->meta,
                    'next'   => $readNext,
                    'random' => $readRandom,
                    'topic'  => $post->topic->first() ?? null,
                ];

                event(new PostViewed($post));

                return view('post', compact('data'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Return similar posts from a given pool.
     *
     * @param \Illuminate\Support\Collection $pool
     * @param \Canvas\Post $post
     * @return mixed
     */
    private function findRelatedViaTaxonomy(Collection $pool, Post $post)
    {
        return collect($pool)->filter(function ($item, $key) use ($post) {
            $matched_tag = array_intersect($item->tags->pluck('slug')->toArray(), $post->tags->pluck('slug')->toArray());
            $matched_topic = array_intersect($item->topic->pluck('slug')->toArray(), $post->topic->pluck('slug')->toArray());

            if ($matched_tag || $matched_topic) {
                return true;
            } else {
                return false;
            }
        });
    }
}
