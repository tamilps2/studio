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
            'avatar'   => $avatar ?? null,
            'lang'     => self::collectLanguageFiles(config('app.locale')),
            'path'     => config('canvas.path'),
            'timezone' => config('app.timezone'),
            'user'     => $user,
        ];
    }

    /**
     * Gather all the language files and rebuild them into into a single
     * consumable JSON object that can be used in the Vue components.
     *
     * @param string
     * @return string
     */
    private static function collectLanguageFiles(string $locale): string
    {
        $langDirectory = dirname(__DIR__, 1).'/resources/lang';
        $files = collect(glob("{$langDirectory}/{$locale}/*.php"));
        $lines = collect();

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $lines->put($filename, include $file);
        }

        return $lines->toJson();
    }
}
