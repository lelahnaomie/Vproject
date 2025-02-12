<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb :page-title="$t('advance')" :directory="$t('datatables')" :icon="'grid'"/>
            </div>
            <div class="col-sm-12 col-md-6 breadcrumb-side-button">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button type="button"
                            class="btn btn-primary btn-with-shadow"
                            data-toggle="modal"
                            @click="openAddEditModal">
                        {{ $t('add') }}
                    </button>
                </div>
            </div>
        </div>

        <app-table
            :id="tableId"
            :options="options"
            @action="getListAction"
        />

        <app-add-modal
            v-if="isAddEditModalActive"
            :table-id="tableId"
            :selected-url="selectedUrl"
            @close-modal="closeAddEditModal"
        />

        <app-delete-modal
            v-if="deleteConfirmationModalActive"
            :preloader="deleteLoader"
            modal-id="demo-delete"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import CoreLibrary from "../../../../../core/helpers/CoreLibrary.js";
import * as actions from "../../../../Config/ApiUrl";
import {TableHelpers} from './mixins/TableHelpers';

export default {
    extends: CoreLibrary,
    name: "DatatableWithFilter",
    mixins: [TableHelpers],
    data() {
        return {
            isAddEditModalActive: false,
            deleteConfirmationModalActive: false,
            deleteLoader: false,
            selectedUrl: '',
            tableId: 'advance-table',
            rowData: {},
            options: {
                name: 'AdvanceTable',
                url: actions.DATATABLE_DATA,
                showHeader: true,
                enableHighlights: true,
                // enableRowSelect: true,
                enableSaveFilter: true,
                columns: [],
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'none',
                        component: 'app-add-modal',
                        modalId: 'demo-add-edit-Modal',

                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash',
                        type: 'none',
                        component: 'app-confirmation-modal',
                        modalId: 'demo-delete',
                    }
                ],
                filters: [
                    {
                        "title": this.$t('date'),
                        "type": "range-picker",
                        "key": "datetime",
                        "option": ["today", "thisMonth", "last7Days", "nextYear"]
                    },
                    {
                        "title": this.$t('age'),
                        "type": "range-filter",
                        "key": "age-range",
                        "maxTitle": this.$t('max_age'),
                        "minTitle": this.$t('min_age'),
                        "sign": ""
                    },
                    {
                        "title": this.$t('status'),
                        "type": "checkbox",
                        "key": "status",
                        "option": [
                            {id: 'active', value: 'Active'},
                            {id: 'inactive', value: 'Inactive'},
                            {id: 'invited', value: 'Invited'},
                        ],
                    },
                    {
                        "title": this.$t('gender'),
                        "type": "radio",
                        "key": "filter-with-gender",
                        "option": [
                            {id: 'male', value: 'Male'},
                            {id: 'female', value: 'Female'},
                            {id: 'other', value: 'Other'},
                        ],
                        "header": {
                            "title": this.$t('want_to_filter_your_list'),
                            "description": this.$t('you_can_filter_your_data_table_which_are_created_based_on_gender'),
                        },
                    },
                    {
                        "title": this.$t('search_and_select'),
                        "type": "drop-down-filter",
                        "key": "search select",
                        "option": [],
                    },
                    {
                        title: "Serverside Search and Select",
                        type: "search-and-select-filter",
                        key: "serverside_select",
                        settings: {
                            url: actions.DATATABLE_SERVER_SEARCH_SELECT, // this url will hit every search action
                            modifire: (v) => {
                                return { id: v.id, name: v.name }
                            },
                            per_page: 10, // default 10, you can change it any number. min 10 encourage to use
                            queryName: 'search_selectable', // default 'search', this key will use for query build link '../endpoind?last_name=shi&moreparam...'
                            loader: 'app-pre-loader', // default app-overlay-loder
                            multiple: true, // default false, if you need to select multiple item so make it true
                            params: {
                                'type': 'type1',
                                'isWanted': true
                            } // params object will be appended with your url after search param like '../endpoint?last_name=shishir&type=type1&isWanted=true'
                        },
                        listValueField: 'name'
                    },
                ],
                showFilter: true,
                showSearch: true,
                showCount: true,
                showClearFilter: true,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "default",
            }
        }
    },
    created() {
        this.options.columns = [...this.tableColumns, this.actionObj];
        this.searchAndSelectFilterOptions();
    },
    methods: {
        /**
         * for open add edit modal
         */
        openAddEditModal() {
            this.isAddEditModalActive = true;
        },

        /**
         * for close add edit modal
         */
        closeAddEditModal() {
            $("#demo-add-edit-Modal").modal('hide');
            this.isAddEditModalActive = false;
            this.searchAndSelectFilterOptions();
            this.reSetData();
        },

        /**
         * $emit Form datatable action
         */
        getListAction(rowData, actionObj, active) {
            this.rowData = rowData;

            if (actionObj.title == 'Delete') {
                this.openDeleteModal();
            } else if (actionObj.title == this.$t('edit')) {
                this.selectedUrl = `${actions.DATATABLE_DATA}/${rowData.id}`;
                this.openAddEditModal();
            }
        },

        /**
         * for open confirmation modal
         */
        openDeleteModal() {
            this.deleteConfirmationModalActive = true;
        },

        /**
         * confirmed $emit Form confirmation modal
         */
        confirmed() {
            let url = `${actions.DATATABLE_DATA}/${this.rowData.id}`;
            this.deleteLoader = true;
            this.axiosDelete(url)
                .then(response => {
                    this.deleteLoader = false;
                    $("#demo-delete").modal('hide');
                    this.cancelled();
                    this.$toastr.s(response.data.message);
                    this.searchAndSelectFilterOptions();
                }).catch(({error}) => {
                    // Trigger after error
                }).finally(() => {
                    this.$hub.$emit('reload-' + this.tableId);
                });
        },

        /**
         * cancelled $emit Form confirmation modal
         */
        cancelled() {
            this.deleteConfirmationModalActive = false;
            this.reSetData();
        },
        reSetData() {
            this.rowData = {};
            this.selectedUrl = '';
        },
        searchAndSelectFilterOptions() {
            this.axiosGet(actions.DATATABLE_SEARCH_SELECT).then(response => {
                let name = this.options.filters.find(element => element.title === this.$t('search_and_select'));

                name.option = response.data.map(name => {
                    return {
                        id: name.name,
                        value: name.name
                    }
                });
            });
        }
    },
}
</script>
