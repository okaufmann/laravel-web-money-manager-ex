<?php

if (!function_exists('mmex_guid')) {
    /**
     * Signs a url and make it available for the given amount of hours.
     * @return string
     */
    function mmex_guid()
    {
        $uuid = Uuid::generate();
        $guid = strtoupper($uuid);

        return sprintf("{%s}", $guid);
    }
}