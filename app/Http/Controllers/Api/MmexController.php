<?php

namespace App\Http\Controllers\Api;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Serializers\MmexArraySerializer;
use App\Services\Mmex\ClientApiService;
use App\Services\Mmex\Functions;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\Request;
use Log;

/**
 * API endpoint for client https://github.com/moneymanagerex/moneymanagerex/blob/master/src/webapp.cpp.
 */
class MmexController extends Controller
{
    /**
     * @var ClientApiService
     */
    private $mmexService;

    /**
     * MmexController constructor.
     *
     * @param ClientApiService $mmexService
     */
    public function __construct(ClientApiService $mmexService)
    {
        $this->mmexService = $mmexService;
    }

    public function handle(Request $request)
    {
        $debugData = ['url' => $request->fullUrl(), 'data' => $request->all(), 'method' => $request->method()];

        Log::debug('Service Request', $debugData);

        $data = $request->all();

        $postData = null;

        if (isset($data['MMEX_Post'])) {
            $postData = json_decode($data['MMEX_Post']);
        } elseif (isset($data['postData'])) {
            $postData = json_decode($data['postData']);
        }

        if ($postData) {
            Log::debug('MmexController, $postData', [$postData]);
        }

        $function = $this->getFunction($data);

        if ($function == Functions::CheckGuid) {
            // TODO
            return $this->returnSuccess();
        }

        if ($function == Functions::CheckApiVersion) {
            return $this->returnText(Constants::$api_version);
        }

        if ($function == Functions::DownloadTransactions) {
            $transactions = $this->mmexService->getTransactions();

            $result = fractal()
                ->collection($transactions)
                ->serializeWith(new MmexArraySerializer())
                ->transformWith(new TransactionTransformer())
                ->toArray();

            // encodes the array as it its (with it keys "0"=> {}, as needed by client)
            $json = json_encode($result, JSON_FORCE_OBJECT);

            return $this->returnText($json);
        }

        if ($function == Functions::DonwloadAttachment) {

            // is something like: Transaction_3_test-receipt-3.png
            $fileName = $data['download_attachment'];

            // extract transaction
            $fileNameParts = explode('_', $fileName);
            $transactionId = $fileNameParts[1];
            $transaction = Transaction::findOrFail($transactionId);

            // get attachment of transaction
            $media = $transaction->getMedia('attachments')->first(function ($item) use ($fileName) {
                return $item->file_name == $fileName;
            });

            // return file as download
            $filePath = $media->getPath();

            $headers = [
                'Content-Type'              => '',
                'Cache-Control'             => 'public',
                'Content-Description'       => 'File Transfer',
                'Content-Disposition'       => 'attachment; filename= '.$fileName,
                'Content-Transfer-Encoding' => 'binary',
            ];

            return response()->file($filePath, $headers);
        }

        if ($function == Functions::DeleteBankAccounts) {
            $this->mmexService->deleteAccounts();

            return $this->returnSuccess();
        }

        if ($function == Functions::ImportBankAccounts) {
            $this->mmexService->importBankAccounts($postData);

            return $this->returnSuccess();
        }

        if ($function == Functions::DeletePayees) {
            $this->mmexService->deletePayees();

            return $this->returnSuccess();
        }

        if ($function == Functions::ImportPayees) {
            $this->mmexService->importPayees($postData);

            return $this->returnSuccess();
        }

        if ($function == Functions::DeleteCategories) {
            $this->mmexService->deleteCategories();

            return $this->returnSuccess();
        }

        if ($function == Functions::ImportCategories) {
            $this->mmexService->importCategories($postData);

            return $this->returnSuccess();
        }

        if ($function == Functions::DeleteTransactions) {
            $transactionId = $data['delete_group'];
            $this->mmexService->deleteTransactions($transactionId);

            return $this->returnSuccess();
        }

        return $data;
    }

    private function getFunction($data)
    {
        if (isset($data[Functions::CheckApiVersion])) {
            return Functions::CheckApiVersion;
        }
        if (isset($data[Functions::CheckGuid])) {
            return Functions::CheckGuid;
        }
        if (isset($data[Functions::DeleteAttachment])) {
            return Functions::DeleteAttachment;
        }
        if (isset($data[Functions::DeleteBankAccounts])) {
            return Functions::DeleteBankAccounts;
        }
        if (isset($data[Functions::DeleteCategories])) {
            return Functions::DeleteCategories;
        }
        if (isset($data[Functions::DeletePayees])) {
            return Functions::DeletePayees;
        }
        if (isset($data[Functions::DeleteTransactions])) {
            return Functions::DeleteTransactions;
        }
        if (isset($data[Functions::DonwloadAttachment])) {
            return Functions::DonwloadAttachment;
        }
        if (isset($data[Functions::DownloadTransactions])) {
            return Functions::DownloadTransactions;
        }
        if (isset($data[Functions::ImportBankAccounts])) {
            return Functions::ImportBankAccounts;
        }
        if (isset($data[Functions::ImportBankAccounts])) {
            return Functions::ImportBankAccounts;
        }
        if (isset($data[Functions::ImportPayees])) {
            return Functions::ImportPayees;
        }
        if (isset($data[Functions::DeleteCategories])) {
            return Functions::DeleteCategories;
        }
        if (isset($data[Functions::ImportCategories])) {
            return Functions::ImportCategories;
        }

        throw new \Exception('No valid function request!');
    }

    private function returnSuccess()
    {
        return $this->returnText(Constants::$operation_succeded);
    }

    private function returnText($text)
    {
        return response($text, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
