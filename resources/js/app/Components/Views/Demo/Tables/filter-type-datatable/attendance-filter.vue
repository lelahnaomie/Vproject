<template>
    <div class="mb-primary col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <h5 class="mb-3">{{ $t('default_filter') }}</h5>
        <app-table :id="'default-filter-table'" :options="options"/>
    </div>
</template>

<script>

    import * as actions from "../../../../../Config/ApiUrl";
    import {TableHelpers} from "../mixins/TableHelpers";
    import CoreLibrary from "../../../../../../core/helpers/CoreLibrary";

    export default {
        name: "attendance-filter",
        mixins: [TableHelpers],
        extends: CoreLibrary,
        data() {
            return {
                options: {
                    name: this.$t('default_filter'),
                    url: actions.ATTENDANCE_DATA,
                    showHeader: true,
                    showCount: true,
                    showClearFilter: true,
                    columns: [],
                    filters: [
                        {
                            "title": this.$t('date'),
                            "type": "range-picker",
                            "key": "date",
                            "option": ["today", "thisMonth", "last7Days", "nextYear"]
                        },
                    ],
                    paginationType: "pagination",
                    responsive: true,
                    rowLimit: 10,
                    orderBy: 'desc',
                    showAction: false,
                    actions: [],
                },
            }
        },
        created() {
            this.options.columns = [...this.tableColumns];
            this.searchAndSelectFilterOptions();
        },
        methods: {
            searchAndSelectFilterOptions() {
                this.axiosGet(actions.DATATABLE_SEARCH_SELECT).then(response => {
                    this.options.filters.push({
                        "title": this.$t('search_and_select'),
                        "type": "drop-down-filter",
                        "key": "search select",
                        "option": [] = response.data.map(name => {
                            return {
                                id: name.name,
                                value: name.name
                            }
                        })
                    })
                });
            }
        }
    }
</script>
