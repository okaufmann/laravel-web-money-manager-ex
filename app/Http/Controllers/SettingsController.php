<?php

namespace App\Http\Controllers;

use App\Services\VersionInfoService;
use Tremby\LaravelGitVersion\GitVersionHelper;

class SettingsController extends Controller
{
    /**
     * @var VersionInfoService
     */
    private $versionInfoService;

    /**
     * SettingController constructor.
     *
     * @param VersionInfoService $versionInfoService
     */
    public function __construct(VersionInfoService $versionInfoService)
    {
        $this->versionInfoService = $versionInfoService;
    }

    public function index()
    {
        $packages = $this->versionInfoService->packageInfo();
        $version = GitVersionHelper::getVersion();

        return view('setting.index', compact('packages','version'));
    }
}
