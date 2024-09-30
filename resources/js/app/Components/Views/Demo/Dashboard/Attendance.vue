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
            <app-breadcrumb :page-title="$t('Attendance')" :directory="$t('dashboard')" :icon="'pie-chart'" />

            <div class="row">
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-6 mb-primary">
                    <app-overlay-loader v-if="!isActiveStudentOverview && !mainPreloader" />
                    <div class="card card-with-shadow border-0 h-100" v-if="isActiveStudentOverview">
                        <div
                            class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center">
                            <!-- <app-search v-model="searchQuery" @input="fetchData" placeholder="Search by Employee ID" /> -->
                            <h5 class="card-title mb-0">{{ $t('Lateness_overview') }}</h5>
                            <ul class="nav tab-filter-menu justify-content-flex-end">
                                <li class="nav-item" v-for="(item, index) in academydashboard.studentOverview.filters"
                                    :key="index">
                                    <a href="#" class="nav-link py-0" :class="{ active: value === item.id }"
                                        @click.prevent="getFilterValue(item.id)">
                                        {{ item.value }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <app-chart type="dough-chart" :height="380"
                                :labels="academydashboard.studentOverview.labels"
                                :data-sets="academydashboard.studentOverview.dataSet" />
                            <div class="chart-data-list">
                                <div class="row">
                                    <div class="col-12"
                                        v-for="(item, index) in academydashboard.schoolOverview.chartElement"
                                        :key="index">
                                        <div class="data-group-item" :style="item.color">
                                            <span class="square" :style="item.background_color" />
                                            {{ item.key }}
                                            <span class="value">{{ item.value }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-with-search border-0 card-with-shadow mb-primary">
                    <div class="card-body">
                        <app-media-object :img-url="imgUrl" :media-title="$t('media_title')"
                                          :media-subtitle="$t('media_subtitle')"/>
                    </div>
                </div>
                    </div>
                </div>

                <app-overlay-loader v-if="!isActiveStudentOverview && !mainPreloader" />
            </div>
        </div>

        <div class="row" v-if="isActivedDefaultData">
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3"
                v-for="(item, index) in academydashboard.defaultData.dataSet" :key="index">
                <app-widget class="mb-primary" :type="'app-widget-with-icon'" :label="item.label"
                    :number="numberFormat(item.value)" :icon="item.icon" />
            </div>
        </div>

        <app-table :options="options" :id="'default-filter-table'" @action="getListAction" :filtered-data="filteredData"
            :search="searchQuery" />

        <app-add-modal v-if="isAddEditModalActive" :table-id="tableId" :selected-url="selectedUrl"
            @close-modal="closeAddEditModal" />

        <app-delete-modal v-if="deleteConfirmationModalActive" :preloader="deleteLoader" modal-id="demo-delete"
            @confirmed="confirmed" @cancelled="cancelled" />
    </div>
</template>

<script>
import { FormMixin } from '../../../../../core/mixins/form/FormMixin';
import { DashboardPreloader } from "./Mixins/DashboardPreloader";
import * as actions from '../../../../Config/ApiUrl';
import { numberFormatter } from "../../../../Helpers/Helpers";
import AppFunction from "../../../../../core/helpers/app/AppFunction";
import CoreLibrary from "../../../../../core/helpers/CoreLibrary.js";
import { TableHelpers } from '../Tables/mixins/AttendanceHelpers';

export default {
    extends: CoreLibrary,
    name: "Dashboard2",
    mixins: [FormMixin, DashboardPreloader, TableHelpers],
    data() {
        return {
            mainPreloader: true,
            isActivedDefaultData: false,
            isActiveStudentOverview: false,
            isActiveSchoolOverview: false,
            isActiveInstructorStudentOverview: false,
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
                        modifier: (value, row) => AppFunction.getAppUrl(value),
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
            filteredData: {
                status: '',
            },
            search: '',
            options: {
                name: 'AdvanceTable',
                url: actions.ATTENDANCE_DATA,
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
                        "option": ["today", "thisMonth", "last7Days", "thisWeek", "lastWeek","lastMonth"]
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
        };
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
        fetchData() {
            let params = {
                employee_id: this.search,
                range:this.range
            };

            this.axiosGet(actions.ATTENDANCE_DATA, { params })
        .then(response => {
            this.filteredData = response.data; 
            this.$hub.$emit('reload-' + this.tableId); 
        })
        .catch(error => {
            console.error('Error fetching data:', error); 
        });

        },
        getSearchValue(value) {
            this.search = value;
            this.fetchData();
        },
        callAction(action) {
            this.$emit('action-' + this.id, this.profile, action, true)
        },
        getImageUrl(profile) {
            if (profile.profile_picture) {
                return AppFunction.getAppUrl(profile.profile_picture);
            }
            return AppFunction.getAppUrl('images/avatar-demo.jpg');
        },
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
            }).catch(({ response }) => { })
                .finally(() => {
                    this.mainPreloader = false;
                    this.countCreatedResponse++;
                });
        },
        studentOverview() {
            let url = actions.STUDENT_OVERVIEW;
            let reqData = {
                params: {
                    key: this.value,
                    search: this.search
                }
            };

            this.axiosGet(url, reqData).then(response => {
                this.academydashboard.studentOverview = response.data;
                this.isActiveStudentOverview = true;
            }).catch(({ response }) => { })
                .finally(() => {
                    this.mainPreloader = false;
                    this.countCreatedResponse++;
                });
        },
        schoolOverview() {
            let url = actions.SCHOOL_OVERVIEW;

            this.axiosGet(url).then(response => {
                this.academydashboard.schoolOverview = response.data;
                this.isActiveSchoolOverview = true;
            }).catch(({ response }) => { })
                .finally(() => {
                    this.mainPreloader = false;
                    this.countCreatedResponse++;
                });
        },
        instructorStudentOverview() {
            let url = actions.INSTRUCTOR_STUDENT_OVERVIEW;

            this.axiosGet(url).then(response => {
                this.academydashboard.instructorStudentOverview = response.data;
                this.isActiveInstructorStudentOverview = true;
            }).catch(({ response }) => { })
                .finally(() => {
                    this.mainPreloader = false;
                    this.countCreatedResponse++;
                });
        },
        numberFormat(value) {
            return numberFormatter(value);
        },
        openAddEditModal() {
            this.isAddEditModalActive = true;
        },
        closeAddEditModal() {
            $("#demo-add-edit-Modal").modal('hide');
            this.isAddEditModalActive = false;
            this.searchAndSelectFilterOptions();
            this.reSetData();
        },
        getListAction(rowData, actionObj, active) {
            this.rowData = rowData;

            if (actionObj.title === 'Delete') {
                this.openDeleteModal();
            } else if (actionObj.title === this.$t('edit')) {
                this.selectedUrl = `${actions.ATTENDANCE_DATA}/${rowData.id}`;
                this.openAddEditModal();
            }
        },
        openDeleteModal() {
            this.deleteConfirmationModalActive = true;
        },
        confirmed() {
            let url = `${actions.ATTENDANCE_DATA}/${this.rowData.id}`;
            this.deleteLoader = true;

            this.axiosDelete(url)
                .then(response => {
                    this.deleteLoader = false;
                    $("#demo-delete").modal('hide');
                    this.cancelled();
                    this.$toastr.s(response.data.message);
                    this.searchAndSelectFilterOptions();
                }).catch(({ error }) => { })
                .finally(() => {
                    this.$hub.$emit('reload-' + this.tableId);
                });
        },
        cancelled() {
            this.deleteConfirmationModalActive = false;
            this.reSetData();
        },
        reSetData() {
            this.rowData = {};
            this.selectedUrl = '';
        },
        searchAndSelectFilterOptions() {
            this.axiosGet(actions.ATTENDANCE_SEARCH_SELECT).then(response => {
                let employee = this.options.filters.find(element => element.title === this.$t('search_and_select'));
                employee.option = response.data.map(employee => ({
                    id: employee.employee_id,
                    value: employee.employee_id
                }));
            });
        },
    }
}
</script>
