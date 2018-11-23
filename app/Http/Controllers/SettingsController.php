<?php

namespace App\Http\Controllers;

use Auth;
use App\Services\VersionInfoService;
use App\Http\Requests\SettingsRequest;
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $packages = $this->versionInfoService->packageInfo();
        $version = GitVersionHelper::getVersion();
        $apiVersion = \App\Services\Mmex\MmexConstants::$api_version;
        $user = Auth::user();
        $authGuid = $user->mmex_guid ?? mmex_guid();
        $userLocale = $user->locale;
        $disableStatus = $user->disable_status;
        $useDatepicker = $user->use_datepicker;

        return view('setting.index', compact('packages', 'version', 'userLocale', 'apiVersion', 'authGuid', 'disableStatus', 'useDatepicker'));
    }

    /**
     * @param SettingsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingsRequest $request)
    {
        $user = Auth::user();

        $user->locale = $request->user_locale;
        $user->mmex_guid = $request->mmex_guid;
        $user->disable_status = $request->disable_status == 'true' ?? false;
        $user->use_datepicker = $request->use_datepicker == 'true' ?? false;

        $user->save();

        return back()->with('status', __('mmex.updated'));
    }
}
