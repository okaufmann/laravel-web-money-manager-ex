$(document).ready(function () {
    kendo.ui.DropDownList.prototype.options =
        $.extend(kendo.ui.DropDownList.prototype.options, {
            noDataTemplate: Lang.get('mmex.no-data-found'),
            optionLabel: Lang.get("mmex.please-choose"),
            filter: "startswith",
            dataTextField: "name",
            dataValueField: "id",
        });

    $("#transaction_type").data("kendoDropDownList", new kendo.ui.DropDownList($("#transaction_type")[0], {
        dataSource: {
            data: mmex.dropDownOptions.types
        },
        change: (e) => {
            let id = e.sender.value();
            let item = e.sender.dataSource.get(id);
            if (item["slug"] === "Transfer") {
                $("#to_account").data("kendoDropDownList").enable(true);
            } else {
                $("#to_account").data("kendoDropDownList").select(null);
                $("#to_account").data("kendoDropDownList").enable(false);
            }
        }
    }));

    $("#transaction_status").data("kendoDropDownList", new kendo.ui.DropDownList($("#transaction_status")[0], {
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

    $("#payee").data("kendoDropDownList", new kendo.ui.DropDownList($("#payee")[0], {
        noDataTemplate: $("#noDataAddNewTemplate").html(),
        change: (e) => {
            let id = e.sender.value();
            let item = e.sender.dataSource.get(id);
            let categoryId = item["category_id"];
            let subCategoryId = item["sub_category_id"];

            if (categoryId) {
                $("#category").data("kendoDropDownList").value(categoryId);

                if (subCategoryId) {
                    $("#subcategory").data("kendoDropDownList").value(subCategoryId);
                }
            }
        },
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

    $("#account").data("kendoDropDownList", new kendo.ui.DropDownList($("#account")[0], {
        dataSource: {
            data: mmex.dropDownOptions.accounts
        },
        change: (e) => {
            let id = e.sender.value();
            let accounts = _.reject(mmex.dropDownOptions.accounts, (a) => a["id"] === parseInt(id));

            $("#to_account").data("kendoDropDownList").dataSource.transport.data = accounts;
            $("#to_account").data("kendoDropDownList").dataSource.read();
        }
    }));

    $("#to_account").data("kendoDropDownList", new kendo.ui.DropDownList($("#to_account")[0], {
        enable: false,
        dataSource: {
            data: mmex.dropDownOptions.accounts
        },
    }));

    $("#category").data("kendoDropDownList", new kendo.ui.DropDownList($("#category")[0], {
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

    $("#subcategory").data("kendoDropDownList", new kendo.ui.DropDownList($("#subcategory")[0], {
        autoBind: false,
        cascadeFrom: "category",
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
        new kendo.ui.NumericTextBox($(elm)[0], {
            format: "c",
            decimals: 2
        });
    });
});