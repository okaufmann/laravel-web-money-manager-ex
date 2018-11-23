<?php

use Ramsey\Uuid\Uuid;

if (! function_exists('mmex_guid')) {
    /**
     * Signs a url and make it available for the given amount of hours.
     *
     * @return string
     * @throws Exception
     */
    function mmex_guid()
    {
        $uuid = Uuid::uuid4();
        $guid = strtoupper($uuid);

        $guid = sprintf('{%s}', mb_strtoupper($guid));

        if (! $guid) {
            throw new \Exception('could not generate new guid!');
        }
    }
}

if (! function_exists('locale_dateformat')) {
    /**
     * Signs a url and make it available for the given amount of hours.
     *
     * @return string
     */
    function locale_dateformat($locale)
    {
        if (starts_with($locale, 'de')) {
            $format = 'd.m.Y';
        } elseif ($locale == 'en_US') {
            $format = 'm/d/Y';
        } elseif ($locale == 'en_GB') {
            $format = 'd/m/Y';
        }

        return $format;
    }
}
