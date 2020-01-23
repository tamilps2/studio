<?php

use Illuminate\Support\Str;

/**
 * Generate a default Gravatar image url from a given email.
 *
 * @param string $email
 * @param int $size
 * @return string
 */
function generateDefaultGravatar(string $email, int $size = 200): string
{
    $emailHash = md5(trim(Str::lower($email)));

    return "https://secure.gravatar.com/avatar/{$emailHash}?s={$size}";
}
