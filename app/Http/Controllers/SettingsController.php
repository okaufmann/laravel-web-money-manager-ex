<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Services\VersionInfoService;
use Auth;
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
        $authGuid = $user->mmex_guid;
        $userLocale = $user->locale;

        return view('setting.index', compact('packages', 'version', 'userLocale', 'apiVersion', 'authGuid'));
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

        $user->save();

        return back()->with('status', trans('Update successful!'));
    }
}
