@inject('fieldValues', 'App\Services\FormFieldOptionService')

@push('footer')
<script type="text/javascript">
    $(document).ready(function () {
        autosize($('textarea'));

        $("[type=date-local]").each((index, elm) => {
            new kendo.ui.DateInput($(elm), {
                value: new Date()
            });
        });
        $(".dropdown-list").each((index, elm) => {
            new kendo.ui.DropDownList($(elm), {
                filter: "startswith",
            });
        });

        $("#category").each((index, elm) => {
            new kendo.ui.DropDownList($(elm), {
                filter: "startswith",
            });
        });

        $("#subcategory").each((index, elm) => {
            new kendo.ui.DropDownList($(elm), {
                autoBind: false,
                cascadeFrom: "category",
                filter: "startswith",
            });
        });


        $(".numeric-currency").each((index, elm) => {
            new kendo.ui.NumericTextBox($(elm), {
                format: "c",
                decimals: 2
            });
        });
    });
</script>
@endpush

<div class="form-group label-static is-empty">
    <label for="transaction_date" class="control-label">@lang('Date')</label>
    <input type="date-local" name="transaction_date" placeholder="Von"
           value="{{old('date_from')}}">
</div>

<div class="form-group label-static is-empty">
    <label for="transaction_status" class="control-label">@lang('Status')</label>
    <select name="transaction_status" class="dropdown-list">
        @foreach($fieldValues->getValues(App\Models\TransactionStatus::class) as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group label-static is-empty">
    <label for="transaction_type" class="control-label">@lang('Type')</label>
    <select name="transaction_type" class="dropdown-list">
        @foreach($fieldValues->getValues(App\Models\TransactionType::class) as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="account" class="control-label">@lang('Account')</label>
    <select name="account" class="dropdown-list">
        @foreach($fieldValues->getValues(App\Models\Account::class) as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="to_account" class="control-label">@lang('to Account')</label>
    <select name="to_account" class="dropdown-list">
        @foreach($fieldValues->getValues(App\Models\Account::class) as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="payee" class="control-label">@lang('Payee')</label>
    <select name="payee" class="dropdown-list">
        @foreach($fieldValues->getValues(App\Models\Payee::class) as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="category" class="control-label">@lang('Category')</label>
    <select id="category" name="category">
        @foreach($fieldValues->getValues(App\Models\Category::class) as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group label-static is-empty">
    <label for="subcategory" class="control-label">@lang('Subcategory')</label>
    <select id="subcategory" name="subcategory"></select>
</div>
<div class="form-group label-static is-empty">
    <label for="amount" class="control-label">@lang('Amount')</label>
    <input name="amount" type="number" title="currency" value="{{old('amount')}}" min="0"
           class="numeric-currency"/>
</div>

<div class="form-group label-static is-empty">
    <label for="notes" class="control-label">@lang('Notes')</label>
    <textarea name="notes" class="form-control" rows="5"
              placeholder="@lang('New transaction notes')"></textarea>
</div>
<div class="form-group label-static is-empty">
    <label for="inputFile" class="control-label">@lang('Take a picture or upload attachments')</label>

    <input type="text" readonly="" class="form-control" placeholder="Browse...">
    <input type="file" multiple="">
</div>