<template>
    <div class="table-responsive">
        <vuetable ref="vuetable"
                  api-url="/api/v1/transactions"
                  :fields="fields"
                  :css="css.table"
                  pagination-path="meta.pagination"
                  @vuetable:pagination-data="onPaginationData"
        ></vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-bootstrap ref="pagination"
                                           @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination-bootstrap>
        </div>
    </div>
</template>

<script lang="ts">
    let Vuetable = require('vuetable-2/src/components/Vuetable');
    let VuetablePaginationBootstrap = require('./VuetablePaginationBootstrap');
    let VuetablePaginationInfo = require('vuetable-2/src/components/VuetablePaginationInfo');

    export default {
        components: {
            Vuetable,
            VuetablePaginationBootstrap,
            VuetablePaginationInfo
        },
        data () {
            return {
                fields: [
                    {
                        title: Lang.get('Date'),
                        name: 'created_at',
                        sortField: 'created_at',
                        callback: 'formatDate'
                    },
                    {
                        title: Lang.get('Account'),
                        name: 'account_name',
                        sortField: 'account_name',
                    },
                    {
                        title: Lang.get('Type'),
                        name: 'type.name',
                    },
                    {
                        title: Lang.get('Payee'),
                        name: 'payee_name',
                        sortField: 'payee_name',
                    },
                    {
                        title: Lang.get('Category'),
                        name: 'category_name',
                        sortField: 'category_name',
                    },
                    {
                        title: Lang.get('Amount'),
                        name: 'amount',
                        sortField: 'amount',
                        callback: 'formatCurrency',
                    },
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
        methods: {
            formatCurrency(value){
                if (value == null) {
                    return '';
                }

                return kendo.toString(value, 'c');
            },
            formatDate (value) {
                if (value == null) {
                    return '';
                }

                return moment(value).format('L');
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page)
            },
            onPaginationData (paginationData) {
                // Transform data from api to match vuetable-2 pagination data structure
                // See: https://github.com/ratiw/vuetable-2-tutorial/wiki/lesson-07#pagination-data-structure
                paginationData.next_page_url = paginationData.links.next;
                paginationData.next_page_url = paginationData.links.next;
                paginationData.last_page = paginationData.total_pages;
                paginationData.last_page = paginationData.total_pages;
                this.$refs.pagination.setPaginationData(paginationData)
            }
        }
    }
</script>
