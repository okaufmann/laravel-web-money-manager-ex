<?php

namespace App\Http\Controllers;

use App\Services\VersionInfoService;
use Symfony\Component\Process\Process;

class SettingController extends Controller
{
    /**
     * @var VersionInfoService
     */
    private $versionInfoService;

    /**
     * SettingController constructor.
     * @param VersionInfoService $versionInfoService
     */
    public function __construct(VersionInfoService $versionInfoService)
    {

        $this->versionInfoService = $versionInfoService;
    }

    public function index()
    {
        $packages = $this->versionInfoService->packageInfo();

        return view('setting.index', compact('packages'));
    }
}
