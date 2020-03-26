<template>
        <!-- Salary Scale Modal -->
        <div ref="salaryScaleModal" id="salaryScaleModal" class="modal custom-modal fade" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Salary Scale Form</h5>
                        <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveSalaryScale()" action="javascript:void(0);">
                            <div class="form-group">
                                <label>Scale <span class="text-danger">*</span></label>
                                <input v-model="salaryScale.scale" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label>Rank <span class="text-danger">*</span></label>
                                <input v-model="salaryScale.rank" class="form-control" type="number" min="0">
                            </div>

                            <div class="form-group">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea v-model="salaryScale.description" class="form-control"></textarea>
                            </div>
                            <div class="submit-section">
                                <button :disabled="isSending || !!!salaryScale.scale || !!!salaryScale.rank" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Salary Scale Modal -->
</template>

<script>
    import SalaryScale from "../../../models/hrms/SalaryScale";
    import {EventBus} from "../../../app";

    export default {
        created(){
            EventBus.$on('editSalaryScale', this.editSalaryScale);
        },
        data() {
            return {
                salaryScale: new SalaryScale(),
                isSending: false,
            };
        },
        methods: {
            editSalaryScale(salaryScale) {
                this.salaryScale = salaryScale;
                console.log(salaryScale);
                $(this.$refs.salaryScaleModal).modal('show');
            },
            async saveSalaryScale() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_SALARY_SCALE',this.salaryScale);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('salaryScaleSaved');
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    this.isSending = false;
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                }

            },
            closePreview() {
                this.salaryScale = new SalaryScale();
                $(this.$refs.salaryScaleModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        }
    }
</script>
