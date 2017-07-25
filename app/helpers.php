<?php

if (!function_exists('mmex_guid')) {
    /**
     * Signs a url and make it available for the given amount of hours.
     *
     * @return string
     */
    function mmex_guid()
    {
        $uuid = Uuid::generate();
        $guid = strtoupper($uuid);

        return sprintf('{%s}', $guid);
    }
}

if (!function_exists('locale_dateformat')) {
    /**
     * Signs a url and make it available for the given amount of hours.
     *
     * @return string
     */
    function locale_dateformat()
    {
        if (starts_with(App::getLocale(), 'de')) {
            $format = 'd.m.Y';
        } elseif (App::getLocale() == 'en_US') {
            $format = 'm/d/Y';
        } elseif (App::getLocale() == 'en_GB') {
            $format = 'd/m/Y';
        }

        return $format;
    }
}
