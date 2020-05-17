<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveSalaryScale()" action="javascript:void(0);">
            <div class="form-group row">
                <label class="col-md-4">Scale <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="salaryScale.scale" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Rank <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="salaryScale.rank" class="form-control" type="number" min="0">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4">Description</label>
                <div class="col-md-8">
                    <textarea v-model="salaryScale.description" class="form-control"></textarea>
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import SalaryScale from "../../../models/hrms/SalaryScale";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        created() {
            EventBus.$on('EDIT_SALARY_SCALE', this.editSalaryScale);
        },
        data() {
            return {
                salaryScale: new SalaryScale(),
                isSending: false,
                isEditing: false,
            };
        },
        computed: {
            title() {
                return (!!this.salaryScale.id) ? "Edit Salary Scale" : "New Salary Scale";
            },
            formInvalid() {
                return this.isSending || !(!!this.salaryScale.scale || !!this.salaryScale.rank);
            }
        },
        methods: {
            editSalaryScale(salaryScale = null) {
                if (salaryScale) {
                    this.salaryScale = deepClone(salaryScale);
                } else {
                    this.salaryScale = new SalaryScale();
                }
                this.isEditing = true;
            },
            async saveSalaryScale() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_SALARY_SCALE', this.salaryScale);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('SALARY_SCALE_SAVED');
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }

            },
            resetForm() {
                this.salaryScale = new SalaryScale();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        }
    }
</script>
