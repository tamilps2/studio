<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\User;
use Canvas\Events\PostViewed;
use Canvas\Post;
use Canvas\Tag;
use Canvas\Topic;
use Canvas\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PostController extends Controller
{
    /**
     * Get all of the posts, tags, and topics.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $posts = Post::published()->withUserMeta()->orderByDesc('published_at')->get();
        $posts->each->append('read_time');

        return response()->json([
            'posts'  => $posts,
            'tags'   => Tag::select(['name', 'slug'])->whereHas('posts')->get(),
            'topics' => Topic::select(['name', 'slug'])->whereHas('posts')->get(),
        ]);
    }

    /**
     * Get all posts for a given username.
     *
     * @param Request $request
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByUsername(Request $request, string $username)
    {
        $userMeta = UserMeta::where('username', $username)->first();

        if ($userMeta) {
            $user = User::where('id', $userMeta->user_id)->first();
            $posts = Post::where('user_id', $userMeta->user_id)
                         ->published()
                         ->withUserMeta()
                         ->orderByDesc('published_at')
                         ->get();

            $avatar = ! empty($userMeta->avatar) ? $userMeta->avatar : generateDefaultGravatar($user->email, 500);

            return response()->json([
                'posts'    => $posts,
                'user'     => $userMeta->user->only(['name', 'email']),
                'avatar'   => $avatar,
                'username' => $userMeta->username,
                'summary'  => $userMeta->summary,
            ]);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * Find a post for a given username.
     *
     * @param Request $request
     * @param string $username
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function findByUsername(Request $request, string $username = null, string $slug = null)
    {
        $userMeta = UserMeta::where('username', $username)->first();

        if ($userMeta) {
            $user = User::where('id', $userMeta->user_id)->first();
            $posts = Post::published()->get();
            $post = $posts->firstWhere('slug', $slug);

            $avatar = ! empty($userMeta->avatar) ? $userMeta->avatar : generateDefaultGravatar($user->email, 200);

            if ($post && $post->published && $post->user->id == $userMeta->user_id) {
                $post->append('read_time');

                $readNext = $posts->sortBy('published_at')->firstWhere('published_at', '>', $post->published_at);

                if ($readNext) {
                    if ($readNext->user_id == $userMeta->user_id) {
                        $readNextUsername = $userMeta->username;
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

                if ($userMeta->user_id == optional($readRandom)->user_id) {
                    $readRandomUsername = $userMeta->username;
                } else {
                    $readRandomUsername = UserMeta::where('user_id', optional($readRandom)->user_id)->pluck('username')->first();
                }

                event(new PostViewed($post));

                return response()->json([
                    'post'     => $post,
                    'tags'     => $post->tags->pluck('name', 'slug'),
                    'topic'    => $post->topic->pluck('name', 'slug'),
                    'user'     => $post->user,
                    'username' => $userMeta->username,
                    'avatar'   => $avatar,
                    'meta'     => $post->meta,
                    'next'     => [
                        'post'     => $readNext,
                        'username' => $readNextUsername ?? null,
                    ],
                    'random'   => [
                        'post'     => $readRandom,
                        'username' => $readRandomUsername ?? null,
                    ],
                ]);
            } else {
                return response()->json(null, 404);
            }
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * Get similar posts from a given pool.
     *
     * @param \Illuminate\Support\Collection $pool
     * @param \Canvas\Post $post
     * @return mixed
     */
    private function findRelatedViaTaxonomy(Collection $pool, Post $post)
    {
        return collect($pool)->filter(function ($item, $key) use ($post) {
            $matched_tag = array_intersect(
                $item->tags->pluck('slug')->toArray(), $post->tags->pluck('slug')->toArray()
            );

            $matched_topic = array_intersect(
                $item->topic->pluck('slug')->toArray(), $post->topic->pluck('slug')->toArray()
            );

            if ($matched_tag || $matched_topic) {
                return true;
            } else {
                return false;
            }
        });
    }
}