<template>
    <div class="table-responsive">
        <vuetable ref="vuetable"
                  :api-url="url"
                  :fields="fields"
                  :css="css.table"
                  :noDataTemplate="noData()"
                  pagination-path="meta.pagination"
                  :sort-order="sortOrder"
                  @vuetable:pagination-data="onPaginationData"
        >
            <template slot="actions" slot-scope="props">
                <div class="form-inline">
                    <a class="btn btn-success btn-raised btn-sm"
                       :href="'/' + props.rowData.id">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-raised btn-sm"
                            @click="onAction('delete-item', props.rowData, props.index)">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
            </template>
        </vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-bootstrap ref="pagination"
                                           @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination-bootstrap>
        </div>
    </div>
</template>

<script>
    let Vuetable = require('vuetable-2/src/components/Vuetable');
    let VuetablePaginationBootstrap = require('./VuetablePaginationBootstrap');
    let VuetablePaginationInfo = require('vuetable-2/src/components/VuetablePaginationInfo');
    import saveState from 'vue-save-state';

    export default {
        mixins: [saveState],
        components: {
            Vuetable,
            VuetablePaginationBootstrap,
            VuetablePaginationInfo
        },
        data() {
            return {
                // Hack api token to url cause axios is not using global config
                url: "/api/v1/transactions?api_token=" + Laravel.apiToken,
                sortOrder: [{field: "transaction_date", sortField: "transaction_date", direction: "desc"}],
                fields: [
                    {
                        title: Lang.get('mmex.date'),
                        name: 'transaction_date',
                        sortField: 'transaction_date',
                        callback: 'formatDate'
                    },
                    {
                        title: Lang.get('mmex.account'),
                        name: 'account_name',
                        sortField: 'account_name',
                    },
                    {
                        title: Lang.get('mmex.type'),
                        name: 'type.name',
                    },
                    {
                        title: Lang.get('mmex.payee'),
                        name: 'payee_name',
                        sortField: 'payee_name',
                        callback: 'formatPayee'
                    },
                    {
                        title: Lang.get('mmex.category'),
                        name: 'category_names',
                        sortField: 'category_names',
                    },
                    {
                        title: Lang.get('mmex.amount'),
                        name: 'amount',
                        sortField: 'amount',
                        callback: 'formatCurrency',
                    },
                    {
                        title: '',
                        name: 'has_attachments',
                        callback: 'hasAttachments',
                    },
                    {
                        name: '__slot:actions',
                        title: Lang.get('mmex.actions'),
                        titleClass: 'center aligned',
                    }
                ],
                css: {
                    table: {
                        tableClass: 'table table-striped table-hover table-condensed',
                        ascendingIcon: 'fa fa-chevron-up',
                        descendingIcon: 'fa fa-chevron-down'
                    },
                    pagination: {
                        wrapperClass: 'pagination',
                        activeClass: 'active',
                        disabledClass: 'disabled',
                        pageClass: 'page',
                        linkClass: 'link',
                    },
                },
            }
        },
        mounted() {
            // ..
        },
        methods: {
            noData() {
                return Lang.get('mmex.no-data-found');
            },
            onAction(action, data, index) {
                console.log(this.$refs.vuetable);

                console.log('slot) action: ' + action, data, index);
                if (action === 'delete-item') {
                    let result = confirm(Lang.get('mmex.delete-transaction'));
                    if (result === true) {
                        axios.delete('transactions/' + data.id).then(() => {
                            this.$refs.vuetable.reload();
                        })
                    }
                }
            },
//            getSortParam(sortOrder) {
//                console.log("getSortParam() : sortOrder:=", sortOrder);
//                return this.$refs.vuetable.getDefaultSortParam();
//            },
            hasAttachments(value) {
                if (!value) {
                    return '';
                }
                return '<i class="fa fa-paperclip"></i>';
            },
            formatCurrency(value) {
                if (value == null) {
                    return '';
                }

                return kendo.toString(value, 'c');
            },
            formatDate(value) {
                if (value == null) {
                    return '';
                }

                return moment(value).format('L');
            },
            formatPayee(value) {
                return value ? value : Lang.get('mmex.none');
            },
            onChangePage(page) {
                this.$refs.vuetable.changePage(page)
            },
            onPaginationData(paginationData) {
                // Transform data from api to match vuetable-2 pagination data structure
                // See: https://github.com/ratiw/vuetable-2-tutorial/wiki/lesson-07#pagination-data-structure
                paginationData.next_page_url = paginationData.links.next;
                paginationData.next_page_url = paginationData.links.next;
                paginationData.last_page = paginationData.total_pages;
                paginationData.last_page = paginationData.total_pages;
                this.$refs.pagination.setPaginationData(paginationData)
            },
            getSaveStateConfig() {
                return {
                    'cacheKey': 'transactionTable',
                    'saveProperties': ['sortOrder'],
                };
            },
        }
    }
</script>
