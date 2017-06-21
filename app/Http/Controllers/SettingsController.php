<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\Transaction;
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $packages = $this->versionInfoService->packageInfo();
        $version = GitVersionHelper::getVersion();
        $status = TransactionStatus::all();
        $types = TransactionType::all();

        return view('setting.index', compact('packages', 'version', 'status', 'types'));
    }

    /**
     * @param SettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingsRequest $request)
    {
        list($status, $types) = $request->getStatusAndTypes();

        // update status
        foreach ($status as $id => $value) {
            TransactionStatus::where('id', $id)->update(['name' => $value, 'slug' => str_slug($value)]);
        }

        // update types
        foreach ($types as $id => $value) {
            TransactionType::where('id', $id)->update(['name' => $value, 'slug' => str_slug($value)]);
        }

        return back();
    }
}
