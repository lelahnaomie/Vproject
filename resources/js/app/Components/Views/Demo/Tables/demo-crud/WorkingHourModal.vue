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
                  ref="form" :data-url='selectedUrl ? `workinghour/${inputs.id}` : `workinghour`'>
                <div class="form-group row align-items-center">
                    <label for="inputs_start" class="col-sm-3 mb-0">
                        {{ $t('Start Time') }}
                    </label>
                    <app-input id="inputs_start"
                               class="col-sm-9"
                               type="time"
                               v-model="inputs.start"
                               :placeholder="$t('text_type_input')"
                               :required="true"/>
                </div>
                <div class="form-group row align-items-center">
                    <label for="inputs_end" class="col-sm-3 mb-0">
                        {{ $t('End Time') }}
                    </label>
                    <app-input id="inputs_end"
                               class="col-sm-9"
                               type="time"
                               v-model="inputs.end"
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
