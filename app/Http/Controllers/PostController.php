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
        $authorMeta = UserMeta::where('username', $username)->first();

        if ($authorMeta) {
            $posts = Post::with('tags', 'topic')->published()->get();
            $post = $posts->firstWhere('slug', $slug);

            if ($post && $post->published && $post->user->id == $authorMeta->user_id) {
                $readNext = $posts->sortBy('published_at')->firstWhere('published_at', '>', $post->published_at);

                if ($readNext) {
                    if ($readNext->user_id == $authorMeta->user_id) {
                        $readNextUsername = $authorMeta->username;
                    } else {
                        $readNextUsername = UserMeta::where('user_id', $readNext->user_id)->pluck('username')->first();
                    }

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

                if ($authorMeta->user_id == optional($readRandom)->user_id) {
                    $readRandomUsername = $authorMeta->username;
                } else {
                    $readRandomUsername = UserMeta::where('user_id', optional($readRandom)->user_id)->pluck('username')->first();
                }

                if ($authorMeta->user_id == optional(auth()->user())->id) {
                    $userAvatar = $authorMeta->avatar;
                } else {
                    $userMeta = UserMeta::forCurrentUser()->first();
                    $emailHash = md5(trim(Str::lower(optional(auth()->user())->email)));
                    $userAvatar = optional($userMeta)->avatar ?? "https://secure.gravatar.com/avatar/{$emailHash}?s=500";
                }

                $data = [
                    'authAvatar' => $userAvatar,
                    'userAvatar' => $authorMeta->avatar,
                    'username'   => $authorMeta->username,
                    'user'       => $post->user,
                    'post'       => $post,
                    'meta'       => $post->meta,
                    'next'       => [
                        'post'     => $readNext,
                        'username' => $readNextUsername ?? null,
                    ],
                    'random'     => [
                        'post'     => $readRandom,
                        'username' => $readRandomUsername ?? null,
                    ],
                    'topic'      => $post->topic->first() ?? null,
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
