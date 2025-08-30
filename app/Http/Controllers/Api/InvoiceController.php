<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'ability: invoice-index, invoice-show'])->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /*
        return InvoiceResource::collection(Invoice::where([
            ['value', '>', 10000],
            ['paid', '=', 1]
        ])->with('user')->get());
        */

        // return InvoiceResource::collection(Invoice::with('user')->get());

        return (new Invoice())->filter($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => ['required'],
            'type'          => ['required', 'max:1'],
            'paid'          => ['required', 'numeric', 'between:0,1'],
            'payment_date'  => ['nullable'],
            'value'         => ['required', 'numeric', 'between:1,9999.99']
        ]);

        if($validator->fails()){
            return $this->error('Invalid data.', 422, $validator->errors());
        }

        $created = Invoice::create($validator->validated());

        if($created){
            return $this->response('Invoice created.', 200, new InvoiceResource($created->load('user')));
        } else {
            return $this->error('Something went wrong.', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        if(Auth::user()->tokenCan('invoice-show')){
            return new InvoiceResource($invoice);
        } else {
            return $this->error('Unanthorized', 403);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => ['required'],
            'type'          => ['required', 'max:1', 'in:B,C,P'],
            'paid'          => ['required', 'numeric', 'between:0,1'],
            'value'         => ['required', 'numeric'],
            'payment_date'  => ['nullable', 'date_format:Y-m-d H:i:s'],
        ]);

        if($validator->fails()){
            return $this->error('Data validation failed.', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $updated = $invoice->update([
            'user_id'       => $validated['user_id'],
            'type'          => $validated['type'],
            'paid'          => $validated['paid'],
            'value'         => $validated['value'],
            'payment_date'  => $validated['paid'] ? $validated['payment_date'] : NULL
        ]);

        if($updated){
            return $this->response('Invoice updated successfully.', 200, new InvoiceResource($invoice->load('user')));
        } else {
            return $this->error('Invoice not updated.', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $deleted = $invoice->delete();

        if($deleted){
            return $this->response('Invoice deleted.', 200);
        } else {
            return $this->error('Invoice deletion failed.', 400);
        }
    }
}
