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

abstract class Transformer
{
    /**
     * Transform a collection of items.
     *
     * @param array $items
     *
     * @return array
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    abstract public function transform($item);
}
