@inject('fieldValues', 'App\Services\FormFieldOptionService')

@push('footer')
@javascript('dropDownOptions', [
'types' => $fieldValues->getMasterData(App\Models\TransactionType::class),
'status' => $fieldValues->getMasterData(App\Models\TransactionStatus::class),
'accounts' => $fieldValues->getUserData(App\Models\Account::class),
])
<script id="noDataAddNewTemplate" type="text/x-kendo-tmpl">
        <div>
            #= Lang.get("mmex.no-data-add-new") #
        </div>
        <br />
        <button class="k-button" onclick="mmex.addPayee('#: instance.element[0].id #', '#: instance.filterInput.val() #')">#= Lang.get("mmex.add-payee") #</button>
</script>
<script type="text/javascript" src="{{mix('js/transaction-form.js')}}"></script>

@endpush

@include('partials.form-errors')

<div class="form-group label-static is-empty">
    <label for="transaction_date" class="control-label">@lang('mmex.type')</label>
    <input type="date-local" value="{{old('transaction_date', $transaction ? $transaction->transaction_date : null)}}"
           name="transaction_date">
</div>

<div class="form-group label-static is-empty">
    <label for="transaction_status" class="control-label">@lang('mmex.status')</label>
    <input type="text"
           value="{{old('transaction_status', $transaction ? $transaction->status_id : null)}}"
           name="transaction_status"
           id="transaction_status"/>
</div>
<div class="form-group label-static is-empty">
    <label for="transaction_type" class="control-label label-required">@lang('mmex.type')</label>
    <input type="text"
           value="{{old('transaction_type', $transaction ? $transaction->type_id: null)}}"
           name="transaction_type"
           id="transaction_type"/>
</div>

<div class="form-group label-static is-empty">
    <label for="account" class="control-label label-required">@lang('mmex.account')</label>
    <input type="text"
           value="{{old('account',$transaction ? $transaction->account_id : null)}}"
           name="account"
           id="account"/>
</div>

<div class="form-group label-static is-empty">
    <label for="to_account" class="control-label">@lang('mmex.to-account')</label>
    <input type="text"
           value="{{old('to_account', $transaction ? $transaction->to_account_id : null)}}"
           name="to_account"
           id="to_account"/>
</div>

<div class="form-group label-static is-empty">
    <label for="payee" class="control-label label-required">@lang('mmex.payee')</label>
    <input type="text"
           value="{{old('payee', $transaction ? $transaction->payee_id : null)}}"
           name="payee"
           id="payee"/>
</div>

<div class="form-group label-static is-empty">
    <label for="category" class="control-label label-required">@lang('mmex.category')</label>
    <input value="{{old('category', $transaction ? $transaction->category_id : null)}}"
           name="category"
           id="category">
</div>
<div class="form-group label-static is-empty">
    <label for="subcategory" class="control-label">@lang('mmex.subcategory')</label>
    <input value="{{old('subcategory', $transaction ? $transaction->sub_category_id : null)}}"
           name="subcategory"
           id="subcategory">
</div>
<div class="form-group label-static is-empty">
    <label for="amount" class="control-label label-required">@lang('mmex.amount')</label>
    <input name="amount" type="number" title="currency"
           value="{{old('amount', $transaction ? $transaction->amount : null)}}"
           min="0"
           class="numeric-currency"/>
</div>

<div class="form-group label-static is-empty">
    <label for="notes" class="control-label">@lang('mmex.notes')</label>
    <textarea name="notes" class="form-control"
              rows="5">{{old('notes', $transaction ? $transaction->notes : null)}}</textarea>
</div>
<div class="form-group label-static is-empty">
    <label for="inputFile" class="control-label">@lang('mmex.add-attachment')</label>

    <input type="text" readonly="" class="form-control" placeholder="@lang("mmex.browse")">
    <input type="file" multiple="" name="attachments[]">
    <span class="help-block">
        {{ini_get('upload_max_filesize')}}/{{ini_get('post_max_size')}}
    </span>
</div>