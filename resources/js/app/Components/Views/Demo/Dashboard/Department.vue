<template>
    <div class="position-relative">
        <div v-if="countCreatedResponse < 4" class="root-preloader position-absolute overlay-loader-wrapper">
            <div class="spinner-bounce d-flex align-items-center dashboard-preloader justify-content-center">
                <span class="bounce1 mr-1"></span>
                <span class="bounce2 mr-1"></span>
                <span class="bounce3 mr-1"></span>
                <span class="bounce4"></span>
            </div>
        </div>
        <div class="content-wrapper">

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <app-breadcrumb :page-title="$t('Department List')" :directory="$t('datatables')" :icon="'grid'" />
                </div>
                <div class="col-sm-12 col-md-6 breadcrumb-side-button">
                    <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                        <button type="button" class="btn btn-primary btn-with-shadow" data-toggle="modal"
                            @click="openAddEditModal">
                            {{ $t('add') }}
                        </button>
                    </div>
                </div>
            </div>


            <app-table :id="tableId" :options="options" @action="getListAction" />

            <app-department-modal v-if="isAddEditModalActive" :table-id="tableId" :selected-url="selectedUrl"
                @close-modal="closeAddEditModal" />

            <app-delete-modal v-if="deleteConfirmationModalActive" :preloader="deleteLoader" modal-id="demo-delete"
                @confirmed="confirmed" @cancelled="cancelled" />
        </div>
    </div>
</template>

<script>
import { FormMixin } from '../../../../../core/mixins/form/FormMixin';
import { DashboardPreloader } from "./Mixins/DashboardPreloader";
import * as actions from '../../../../Config/ApiUrl';
import { numberFormatter } from "../../../../Helpers/Helpers";
import AppFunction from "../../../../../core/helpers/app/AppFunction";
import CoreLibrary from "../../../../../core/helpers/CoreLibrary.js";
import { TableHelpers } from '../Tables/mixins/DepartmentHelpers';

export default {
    extends: CoreLibrary,
    name: "Dashboard2",
    mixins: [FormMixin, DashboardPreloader, TableHelpers],
    data() {
        return {
            /* Loader section and active/inactive section */
            mainPreloader: true,

            isActivedDefaultData: false,
            isActiveStudentOverview: false,
            isActiveSchoolOverview: false,
            isActiveInstructorStudentOverview: false,
            /* end */

            value: 'by_week',
            academydashboard: {},
            availableCourses: {
                name: 'Available Courses',
                url: actions.DEFAULT_ATTENDANCE_INFO,
                datatableWrapper: false,
                tableShadow: false,
                showHeader: false,
                managePagination: false,
                columns: [
                    {
                        title: this.$t('course'),
                        type: 'media-object',
                        key: 'image',
                        mediaTitleKey: 'name',
                        mediaSubtitleKey: 'instructor-name',
                        default: AppFunction.getAppUrl('images/avatar-demo.jpg'),
                        modifier: (value, row) => {
                            return AppFunction.getAppUrl(value); // imag url
                        },
                        isVisible: true
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-class',
                        key: 'status',
                        isVisible: true,
                        modifier: (value) => {
                            if (value === 'Basic') return "badge badge-sm badge-pill badge-success";
                            else if (value === 'Special') return "badge badge-sm badge-pill badge-warning";
                            return "badge badge-sm badge-pill badge-danger";
                        }
                    }
                ],
                showFilter: false,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 4,
                orderBy: 'desc',
                showAction: false,
                actions: [],
            },
            isAddEditModalActive: false,
            deleteConfirmationModalActive: false,
            deleteLoader: false,
            selectedUrl: '',
            tableId: 'advance-table',
            rowData: {},
            options: {
                name: 'AdvanceTable',
                url: actions.DEPARTMENT_DATA,
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
                        "key": "date",
                        "option": ["today", "thisMonth", "last7Days", "nextYear"]
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
        this.defaultData();
        this.studentOverview();
        this.schoolOverview();
        this.instructorStudentOverview();
        this.options.columns = [...this.tableColumns, this.actionObj];
        this.searchAndSelectFilterOptions();
    },
    methods: {
        getFilterValue(item) {
            this.value = item;
            this.isActiveStudentOverview = false;
            this.studentOverview();
        },
        defaultData() {
            let url = actions.DEFAULT_ACADEMIC_INFO;

            this.axiosGet(url).then(response => {

                this.academydashboard.defaultData = response.data;
                this.isActivedDefaultData = true;

            }).catch(({ response }) => {

            }).finally(() => {
                this.mainPreloader = false;
                this.countCreatedResponse++;
            });
        },
        studentOverview() {

            let url = actions.STUDENT_OVERVIEW, reqData = {};

            reqData.params = {
                key: this.value
            };

            this.axiosGet(url, reqData).then(response => {

                this.academydashboard.studentOverview = response.data;
                this.isActiveStudentOverview = true;

            }).catch(({ response }) => {

            }).finally(() => {
                this.mainPreloader = false;
                this.countCreatedResponse++;
            });
        },
        schoolOverview() {
            let url = actions.SCHOOL_OVERVIEW;

            this.axiosGet(url).then(response => {

                this.academydashboard.schoolOverview = response.data;
                this.isActiveSchoolOverview = true;

            }).catch(({ response }) => {

            }).finally(() => {
                this.mainPreloader = false;
                this.countCreatedResponse++;
            });
        },
        instructorStudentOverview() {

            let url = actions.INSTRUCTOR_STUDENT_OVERVIEW;

            this.axiosGet(url).then(response => {

                this.academydashboard.instructorStudentOverview = response.data;
                this.isActiveInstructorStudentOverview = true;

            }).catch(({ response }) => {

            }).finally(() => {
                this.mainPreloader = false;
                this.countCreatedResponse++;
            });
        },
        numberFormat(value) {
            return numberFormatter(value);
        },
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
                this.selectedUrl = `${actions.DEPARTMENT_DATA}/${rowData.id}`;
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
            let url = `${actions.DEPARTMENT_DATA}/${this.rowData.id}`;
            this.deleteLoader = true;
            this.axiosDelete(url)
                .then(response => {
                    this.deleteLoader = false;
                    $("#demo-delete").modal('hide');
                    this.cancelled();
                    this.$toastr.s(response.data.message);
                    this.searchAndSelectFilterOptions();
                }).catch(({ error }) => {
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
    }
}
</script>
