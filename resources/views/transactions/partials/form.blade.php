@inject('fieldValues', 'App\Services\FormFieldOptionService')

@push('footer')
@javascript('dropDownOptions', [
'types' => $fieldValues->getValues(App\Models\TransactionType::class),
'status' => $fieldValues->getValues(App\Models\TransactionStatus::class),
'accounts' => $fieldValues->getValues(App\Models\Account::class),

])
<script id="noDataAddNewTemplate" type="text/x-kendo-tmpl">
        <div>
            No data found. Do you want to add new item - '#: instance.filterInput.val() #' ?
        </div>
        <br />
        <button class="k-button" onclick="mmex.addPayee('#: instance.element[0].id #', '#: instance.filterInput.val() #')">Add new item</button>
</script>
<script type="text/javascript">
    $(document).ready(function () {

        kendo.ui.DropDownList.prototype.options =
            $.extend(kendo.ui.DropDownList.prototype.options, {
                noDataTemplate: Lang.get('No Data found.'),
                optionLabel: Lang.get("Please Choose"),
                filter: "startswith",
                dataTextField: "name",
                dataValueField: "id",
            });

        $("#transaction_type").data("kendoDropDownList", new kendo.ui.DropDownList($("#transaction_type"), {
            dataSource: {
                data: mmex.dropDownOptions.types
            },
            change: (e) => {
                let id = e.sender.value();
                let item = e.sender.dataSource.get(id);
                if (item.slug === "Transfer") {
                    $("#to_account").data("kendoDropDownList").enable();
                } else {
                    $("#to_account").data("kendoDropDownList").reset();
                    $("#to_account").data("kendoDropDownList").enable(false);
                }
            }
        }));

        $("#transaction_status").data("kendoDropDownList", new kendo.ui.DropDownList($("#transaction_status"), {
            dataSource: {
                data: mmex.dropDownOptions.status
            },
        }));

        mmex.addPayee = (widgetId, value) => {
            let widget = $("#" + widgetId).data("kendoDropDownList");
            let dataSource = widget.dataSource;

            dataSource.add({
                name: value
            });

            dataSource.one("sync", () => {
                widget.select(dataSource.view().length - 1);
            });

            dataSource.sync();
        };

        $("#payee").data("kendoDropDownList", new kendo.ui.DropDownList($("#payee"), {
            noDataTemplate: $("#noDataAddNewTemplate").html(),
            dataSource: {
                batch: true,
                transport: {
                    read: '/api/v1/payee',
                    create: {
                        url: "/api/v1/payee",
                        dataType: "json",
                        method: "POST"
                    },
                    parameterMap: function (options, operation) {
                        if (operation !== "read" && options.models) {
                            return _.first(options.models);
                        }
                    }
                },
                schema: {
                    model: {
                        id: "id",
                        fields: {
                            id: {type: "number"},
                            name: {type: "string"}
                        }
                    },
                    data: "data"
                }
            },

        }));

        $("#account").data("kendoDropDownList", new kendo.ui.DropDownList($("#account"), {
            dataSource: {
                data: mmex.dropDownOptions.accounts
            },
            change: (e) => {
                let id = e.sender.value();
                let accounts = _.reject(mmex.dropDownOptions.accounts, (a) => a.id === parseInt(id));

                $("#to_account").data("kendoDropDownList").dataSource.transport.data = accounts;
                $("#to_account").data("kendoDropDownList").dataSource.read();
            }
        }));

        $("#to_account").data("kendoDropDownList", new kendo.ui.DropDownList($("#to_account"), {
            enable: false,
            dataSource: {
                data: mmex.dropDownOptions.accounts
            },
        }));

        $("#category").data("kendoDropDownList", new kendo.ui.DropDownList($("#category"), {
            height: 300,
            dataSource: {
                serverFiltering: false,
                transport: {
                    read: "/api/v1/category/"
                },
                schema: {
                    data: "data"
                }
            }
        }));

        $("#subcategory").data("kendoDropDownList", new kendo.ui.DropDownList($("#subcategory"), {
            autoBind: false,
            cascadeFrom: "category",
            optionLabel: Lang.get("Please Choose"),
            height: 300,
            dataSource: {
                serverFiltering: true,
                transport: {
                    read: {
                        dataType: "json",
                        url: function () {
                            return "/api/v1/category/" + $("#category").data("kendoDropDownList").value() + "/subcategories"
                        }
                    }
                },
                schema: {
                    data: "data"
                }
            }
        }));

        $(".numeric-currency").each((index, elm) => {
            new kendo.ui.NumericTextBox($(elm), {
                format: "c",
                decimals: 2
            });
        });
    });
</script>

@endpush

@include('partials.form-errors')

<input type="hidden" name="id" value="{{old('id', $transaction ? $transaction->id : null)}}">

<div class="form-group label-static is-empty">
    <label for="transaction_date" class="control-label">@lang('Date')</label>
    <input type="date-local" value="{{old('transaction_date', $transaction ? $transaction->transaction_date : null)}}"
           name="transaction_date">
</div>

<div class="form-group label-static is-empty">
    <label for="transaction_status" class="control-label">@lang('Status')</label>
    <input type="text"
           value="{{old('transaction_status', $transaction ? $transaction->status_id : null)}}"
           name="transaction_status"
           id="transaction_status"/>
</div>
<div class="form-group label-static is-empty">
    <label for="transaction_type" class="control-label">@lang('Type')</label>
    <input type="text"
           value="{{old('transaction_type', $transaction ? $transaction->type_id: null)}}"
           name="transaction_type"
           id="transaction_type"/>
</div>

<div class="form-group label-static is-empty">
    <label for="account" class="control-label">@lang('Account')</label>
    <input type="text"
           value="{{old('account',$transaction ? $transaction->account_id : null)}}"
           name="account"
           id="account"/>
</div>

<div class="form-group label-static is-empty">
    <label for="to_account" class="control-label">@lang('to Account')</label>
    <input type="text"
           value="{{old('to_account', $transaction ? $transaction->to_account_id : null)}}"
           name="to_account"
           id="to_account"/>
</div>

<div class="form-group label-static is-empty">
    <label for="payee" class="control-label">@lang('Payee')</label>
    <input type="text"
           value="{{old('payee', $transaction ? $transaction->payee_id : null)}}"
           name="payee"
           id="payee"/>
</div>

<div class="form-group label-static is-empty">
    <label for="category" class="control-label">@lang('Category')</label>
    <input value="{{old('category', $transaction ? $transaction->category_id : null)}}"
           name="category"
           id="category">
</div>
<div class="form-group label-static is-empty">
    <label for="subcategory" class="control-label">@lang('Subcategory')</label>
    <input value="{{old('subcategory', $transaction ? $transaction->sub_category_id : null)}}"
           name="subcategory"
           id="subcategory">
</div>
<div class="form-group label-static is-empty">
    <label for="amount" class="control-label">@lang('Amount')</label>
    <input name="amount" type="number" title="currency"
           value="{{old('amount', $transaction ? $transaction->amount : null)}}"
           min="0"
           class="numeric-currency"/>
</div>

<div class="form-group label-static is-empty">
    <label for="notes" class="control-label">@lang('Notes')</label>
    <textarea name="notes" class="form-control" rows="5"
              placeholder="@lang('New transaction notes')">{{old('notes', $transaction ? $transaction->notes : null)}}</textarea>
</div>
<div class="form-group label-static is-empty">
    <label for="inputFile" class="control-label">@lang('Take a picture or upload attachments')</label>

    <input type="text" readonly="" class="form-control" placeholder="Browse...">
    <input type="file" multiple="" name="attachments[]">
    <span class="help-block">
        {{ini_get('upload_max_filesize')}}/{{ini_get('post_max_size')}}
    </span>
</div>