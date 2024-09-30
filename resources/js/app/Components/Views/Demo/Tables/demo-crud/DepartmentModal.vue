<template>
    <modal :modal-id="modalId"
                     :title="modalTitle"
                     :preloader="preloader"
                     @submit="submit"
                     @close-modal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form" :data-url='selectedUrl ? `department/${inputs.id}` : `department`'>
                <div class="form-group row align-items-center">
                    <label for="inputs_name" class="col-sm-3 mb-0">
                        {{ $t('name') }}
                    </label>
                    <app-input id="inputs_name"
                               class="col-sm-9"
                               type="text"
                               v-model="inputs.name"
                               :placeholder="$t('text_type_input')"
                               :required="true"/>
                </div>
                
            </form>
        </template>
    </modal>
</template>

<script>
    import {FormMixin} from '../../../../../../core/mixins/form/FormMixin.js';
    import {ModalMixin} from "../../../../../Mixins/ModalMixin";
   
    export default {
        name: "AddModal",
        mixins: [FormMixin, ModalMixin],
        props: {
            tableId: String
        },
        data() {
            return {
                preloader: false,
                inputs: {
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
