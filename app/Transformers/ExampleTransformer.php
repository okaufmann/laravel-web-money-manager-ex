<?php

/*
 * mighty-movies-web-app-2
 *
 * This File belongs to to Project mighty-movies-web-app-2
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>, Natthakit Khamso <natthakit.khamso@gmail.com>
 * @version 1.0
 */

namespace App\Transformers;

class UserTransformer extends Transformer
{
    public function transform($item)
    {
        return [
            'id'       => $item->id,
            'name'     => $item->userProfile->name,
            'email'    => $item->email,
            'about'    => $item->userProfile->about,
            'zip'      => $item->userProfile->zip,
            'location' => $item->userProfile->location,
            'avatar'   => $item->userProfile->avatar,
        ];
    }
}
