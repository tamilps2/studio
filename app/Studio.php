<?php

namespace App;

use Canvas\UserMeta;

class Studio
{
    /**
     * Build a global JavaScript object for the Vue app.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        $user = optional(auth()->user())->email_verified_at ? auth()->user() : null;

        if ($user) {
            $metaData = UserMeta::where('user_id', $user->id)->first();
            $avatar = !empty(optional($metaData)->avatar) ? $metaData->avatar : generateDefaultGravatar($user->email);
        }

        return [
            'avatar' => $avatar ?? null,
            'canvas_path' => config('canvas.path'),
            'studio_path' => config('studio.path'),
            'timezone' => config('app.timezone'),
            'user' => $user,
        ];
    }
}
