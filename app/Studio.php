<?php

namespace App;

use Canvas\UserMeta;
use Illuminate\Support\Str;

class Studio
{
    /**
     * Build a global JavaScript object for the Vue app.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        $metaData = UserMeta::where('user_id', optional(auth()->user())->id);
        $emailHash = md5(trim(Str::lower(optional(auth()->user())->email)));

        return [
            'user'   => optional(auth()->user())->only(['name', 'email']),
            'avatar' => optional($metaData)->avatar ?? "https://secure.gravatar.com/avatar/{$emailHash}?s=500",
        ];
    }
}
