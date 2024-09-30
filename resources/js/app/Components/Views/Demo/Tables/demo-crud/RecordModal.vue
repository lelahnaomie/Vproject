<template>
    <modal :modal-id="modalId" :title="modalTitle" :preloader="preloader" @submit="submit" @close-modal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form"
                :data-url='selectedUrl ? `record/${inputs.id}` : `record`'>
                <div class="form-group row align-items-center">
                    <label for="inputs_user_id" class="col-sm-3 mb-0">
                        {{ $t('User ID') }}
                    </label>
                    <app-input id="inputs_user_id" class="col-sm-9" type="text" v-model="inputs.user_id"
                        :placeholder="$t('text_type_input')" :required="true" />

                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_name" class="col-sm-3 mb-0">
                        {{ $t('Name') }}
                    </label>
                    <app-input id="inputs_name" class="col-sm-9" type="text" v-model="inputs.name"
                        :placeholder="$t('text_type_input')" :required="true" />

                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_role" class="col-sm-3 mb-0">
                        {{ $t('Role') }}
                    </label>
                    <app-input id="inputs_role" class="col-sm-9" type="text" v-model="inputs.role"
                        :placeholder="$t('text_type_input')" :required="true" />
                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_password" class="col-sm-3 mb-0">
                        {{ $t('Password') }}
                    </label>
                    <app-input id="inputs_password" class="col-sm-9" type="password" v-model="inputs.password"
                        :placeholder="$t('password_type_input')" :required="true"/>
                </div>
            </form>
        </template>
    </modal>
</template>

<script> 
import {FormMixin} from '../../../../../../core/mixins/form/FormMixin.js';
import {ModalMixin} from "../../../../../Mixins/ModalMixin";

export default {
    name: "recordModal",
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
            modalId: 'demo-add-edit-Modal',
            modalTitle: this.$t('add'),
        }
    },
    created() {
        if (this.selectedUrl) {
            this.modalTitle = this.$t('edit');
            this.preloader = true;
        }
    },
    methods: {
        submit() {
            this.inputs.phone = this.phone;
            this.save(this.inputs);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
        },

        afterSuccessFromGetEditData(response) {
            if (response.data.phone) this.phone = response.data.phone;
            this.inputs = response.data;
            this.preloader = false;
        },
    },
}
</script>
