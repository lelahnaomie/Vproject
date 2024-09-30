<template>
    <modal :modal-id="modalId" :title="modalTitle" :preloader="preloader" @submit="submit" @close-modal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form"
                :data-url='selectedUrl ? `employee/${inputs.id}` : `employee`'>
                <div class="form-group row align-items-center">
                    <label for="inputs_profile_picture" class="col-sm-3 mb-0">
                        {{ $t('Profile Picture') }}
                    </label>
                    <div class="profile-pic-wrapper position-relative">
                                    <app-overlay-loader v-if="preloader"/>
                                    <app-input v-else
                                               id="user_image"
                                               class="circle mx-xl-auto"
                                               :wrapper-class="'circle mx-xl-auto'"
                                               name="user_image"
                                               type="custom-file-upload"
                                               v-model="inputs.profile_picture"
                                               :generate-file-url="false"
                                               :label="$t('change')"/>
                                </div>

                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_first_name" class="col-sm-3 mb-0">
                        {{ $t('First name') }}
                    </label>
                    <app-input id="inputs_first_name" class="col-sm-9" type="text" v-model="inputs.first_name"
                        :placeholder="$t('text_type_input')" :required="true" />

                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_last_name" class="col-sm-3 mb-0">
                        {{ $t('Last name') }}
                    </label>
                    <app-input id="inputs_last_name" class="col-sm-9" type="text" v-model="inputs.last_name"
                        :placeholder="$t('text_type_input')" :required="true" />

                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_email" class="col-sm-3 mb-0">
                        {{ $t('email') }}
                    </label>
                    <app-input id="inputs_email" class="col-sm-9" type="email" v-model="inputs.email"
                        :placeholder="$t('email_type_input')" :required="true" />
                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_department" class="col-sm-3 mb-0">
                        {{ $t('department') }}
                    </label>
                    <app-input id="inputs_department" class="col-sm-9" type="select" v-model="inputs.department_id"
                        :required="true" 
                        :placeholder="$t('department_type_input')"
                        :list="departmentLists" 
                        :listValueField="`name`"/>
                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_attendance_id" class="col-sm-3 mb-0">
                        {{ $t('Attendance ID') }}
                    </label>
                    <app-input id="inputs_attendance_id" class="col-sm-9" type="select" v-model="inputs.attendance_id"
                        :required="true" 
                        :placeholder="$t('attendance_type_input')"
                        :list="attendanceIdLists" />

                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_working_hours" class="col-sm-3 mb-0">
                        {{ $t('Working Hours') }}
                    </label>
                    <app-input id="inputs_hours" class="col-sm-9" type="select" v-model="inputs.workinghour_id"
                        :placeholder="$t('Working_Hours_type_input')" :required="true" :list="hoursLists"  :listValueField="`full_time`" />
                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_tel_number" class="col-sm-3 mb-0">
                        {{ $t('Telephone Number') }}
                    </label>
                    <app-input id="inputs_tel_number" class="col-sm-9" type="tel-input"  v-model="tel_number"
                        :placeholder="$t('type_tel_number')" :required="true" />
                </div>

            </form>
        </template>
    </modal>
</template>

<script>
import { FormMixin } from '../../../../../../core/mixins/form/FormMixin.js';
import { ModalMixin } from "../../../../../Mixins/ModalMixin";
import * as actions from '../../../../../Config/ApiUrl';

export default {
    name: "employeeModal",
    mixins: [FormMixin, ModalMixin],
    props: {
        tableId: String
    },
    data() {
        return {
            preloader: false,
            inputs: {
                gender: 'male',
            },
            tel_number: '',
            departmentLists:'',
            hoursLists:'',
            attendanceIdLists: [
                {id: '', value: "Choose One",},   
                { id: '1', value: "34" },
                { id: '2', value: "10" },
                { id: '3', value: "21" },
                { id: '36', value: "36" },
                { id: '9', value: "9" },
            ],
            modalId: 'demo-add-edit-Modal',
            modalTitle: this.$t('add'),
        }
    },
    created() {
        if (this.selectedUrl) {
            this.modalTitle = this.$t('edit');
            this.preloader = true;
        }
        this.getDepartment();
        this.getWorkingHour();

    },
    methods: {
        submit() {
            this.inputs.tel_number = this.tel_number;
            this.save(this.inputs);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
        },

        afterSuccessFromGetEditData(response) {
            if (response.data.tel_number) this.tel_number = response.data.tel_number;
            this.inputs = response.data;
            this.preloader = false;
        },
        getDepartment() {
            let url = actions.DEPARTMENT_DATA;

            this.axiosGet(url).then(response => {

                this.departmentLists = response.data.data;
                this.departmentLists.unshift({id: '', name: "Choose One"})
               

            }).catch(({ response }) => {

            }).finally(() => {
                this.mainPreloader = false;
                this.countCreatedResponse++;
            });
        },
        getWorkingHour() {
            let url = actions.WORKINGHOUR_DATA;

            this.axiosGet(url).then(response => {

                this. hoursLists = response.data.data;
                this. hoursLists.unshift({id: '', full_time: "Choose One"})
              

            }).catch(({ response }) => {

            }).finally(() => {
                this.mainPreloader = false;
                this.countCreatedResponse++;
            });
        },
    },
}
</script>
