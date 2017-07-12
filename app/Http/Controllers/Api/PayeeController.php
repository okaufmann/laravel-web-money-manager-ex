<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\PayeeTransformer;
use Auth;
use Illuminate\Http\Request;

class PayeeController extends Controller
{
    /**
     * @return $this
     */
    public function index()
    {
        $data = Auth::user()->payees()->orderBy('name')->get();

        return fractal()
            ->collection($data)
            ->transformWith(new PayeeTransformer())
            ->toArray();
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|string']);

        $payee = Auth::user()->payees()->create($request->all());

        return fractal()
            ->item($payee)
            ->transformWith(new PayeeTransformer())
            ->toArray();
    }
}
