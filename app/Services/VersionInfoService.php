<?php
/*
 * laravel-money-manager-ex
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 */

namespace App\Services;


use Cache;
use Symfony\Component\Process\Process;

class VersionInfoService
{
    /**
     * @return mixed|string
     */
    public function packageInfo()
    {
        return Cache::get('mmex-version-info', function () {
            return $this->getInstalledPackages();
        });
    }

    /**
     * @return mixed
     */
    protected function getInstalledPackages()
    {
        $process = new Process('cd .. && composer show -i -f json');
        $process->run();
        $output = $process->getOutput();
        $output = json_decode($output, true);

        $r = $output['installed'];

        return $r;
    }

}