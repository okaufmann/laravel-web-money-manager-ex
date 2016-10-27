<?php

namespace App\Http\Controllers\Api;

use App\Constants;
use App\Services\MmexFunctions;
use App\Services\MmexService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class MmexController extends Controller
{
    /**
     * @var MmexService
     */
    private $mmexService;

    /**
     * MmexController constructor.
     * @param MmexService $mmexService
     */
    public function __construct(MmexService $mmexService)
    {

        $this->mmexService = $mmexService;
    }

    public function handle(Request $request)
    {
        $debugData = ["url" => $request->fullUrl(), "data" => $request->all()];

        Log::debug("Service Request", $debugData);

        $data = $request->all();

        $postData = null;

        if (isset($data['MMEX_Post'])) {
            $postData = json_decode($data['MMEX_Post']);
            Log::debug('MmexController, $postData', [$postData]);
        }

        $function = $this->getFunction($data);

        // TODO: Write Tests
        if ($function == MmexFunctions::CheckGuid) {
            return $this->returnText(Constants::$operation_succeded);
        }

        if ($function == MmexFunctions::CheckApiVersion) {
            return $this->returnText(Constants::$api_version);
        }

        // TODO: remove dummy data
        // TODO: Write Tests
        if ($function == MmexFunctions::DownloadTransactions) {
            return $this->returnText($this->mmexService->getTransactions());
        }

        // TODO: Write Tests
        if ($function == MmexFunctions::DeleteBankAccounts) {
            $this->mmexService->deleteAccounts();
            return $this->returnText(Constants::$operation_succeded);
        }

        // TODO: Write Tests
        if ($function == MmexFunctions::ImportBankAccounts) {
            $this->mmexService->importBankAccounts($postData);
            return $this->returnText(Constants::$operation_succeded);
        }

        // TODO: Write Tests
        if ($function == MmexFunctions::DeletePayees) {
            $this->mmexService->deletePayees();
            return $this->returnText(Constants::$operation_succeded);
        }

        // TODO: Write Tests
        if ($function == MmexFunctions::ImportPayees) {
            $this->mmexService->importPayees($postData);
            return $this->returnText(Constants::$operation_succeded);
        }

        // TODO: Write Tests
        if ($function == MmexFunctions::DeleteCategories) {
            $this->mmexService->deleteCategories();
            return $this->returnText(Constants::$operation_succeded);
        }

        // TODO: Write Tests
        if ($function == MmexFunctions::ImportCategories) {
            $this->mmexService->importCategories($postData);
            return $this->returnText(Constants::$operation_succeded);
        }

        return $data;
    }

    private function returnText($text)
    {
        return response($text, 200)
            ->header('Content-Type', 'text/plain');
    }

    private function getFunction($data)
    {
        if (isset($data[MmexFunctions::CheckApiVersion])) {
            return MmexFunctions::CheckApiVersion;
        }
        if (isset($data[MmexFunctions::CheckGuid])) {
            return MmexFunctions::CheckGuid;
        }
        if (isset($data[MmexFunctions::DeleteAttachment])) {
            return MmexFunctions::DeleteAttachment;
        }
        if (isset($data[MmexFunctions::DeleteBankAccounts])) {
            return MmexFunctions::DeleteBankAccounts;
        }
        if (isset($data[MmexFunctions::DeleteCategories])) {
            return MmexFunctions::DeleteCategories;
        }
        if (isset($data[MmexFunctions::DeletePayees])) {
            return MmexFunctions::DeletePayees;
        }
        if (isset($data[MmexFunctions::DeleteTransactions])) {
            return MmexFunctions::DeleteTransactions;
        }
        if (isset($data[MmexFunctions::DonwloadAttachment])) {
            return MmexFunctions::DonwloadAttachment;
        }
        if (isset($data[MmexFunctions::DownloadTransactions])) {
            return MmexFunctions::DownloadTransactions;
        }
        if (isset($data[MmexFunctions::ImportBankAccounts])) {
            return MmexFunctions::ImportBankAccounts;
        }
        if (isset($data[MmexFunctions::ImportBankAccounts])) {
            return MmexFunctions::ImportBankAccounts;
        }
        if (isset($data[MmexFunctions::ImportPayees])) {
            return MmexFunctions::ImportPayees;
        }

        throw new \Exception("No valid function request!");
    }
}
