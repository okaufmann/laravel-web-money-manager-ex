<?php

namespace App\Http\Controllers;

use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Services\VersionInfoService;
use Illuminate\Http\Request;
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
        $status = TransactionStatus::all();
        $types = TransactionType::all();

        return view('setting.index', compact('packages', 'version', 'status', 'types'));
    }

    public function update(Request $request)
    {


        dd($request->all());
    }
}
