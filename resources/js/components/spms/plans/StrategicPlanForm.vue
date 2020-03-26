<template>
    <!-- Add Plan Modal -->
    <div ref="planModal" id="planModal" class="modal custom-modal fade"
         role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Information</h5>
                    <button
                        @click="closePreview()"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="savePlan">
                        <div class="form-group row">
                            <label class="col-sm-4">
                                Title
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" v-model="plan.name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Theme</label>
                            <div class="col-sm-8">
                                <textarea v-model="plan.theme" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Vision</label>
                            <div class="col-sm-8">
                                <textarea v-model="plan.vision" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Mission</label>
                            <div class="col-sm-8">
                                <textarea v-model="plan.mission" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Values</label>
                            <div class="col-sm-8">
                                <textarea v-model="plan.values" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Monitor Frequency</label>
                            <div class="col-sm-8">
                                <select v-model="plan.frequency" class="form-control">
                                    <option value="">Select...</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="4-months">Every 4 months</option>
                                    <option value="6-months">Every 6 months</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Start Date</label>
                                <app-date-range-picker
                                    v-model="plan.startDate"
                                    :value="plan.startDate"
                                    :config="dateConfig"
                                />
                            </div>

                            <div class="col-sm-6">
                                <label>End Date</label>
                                <app-date-range-picker
                                    v-model="plan.endDate"
                                    :value="plan.endDate"
                                    :config="dateConfig">
                                </app-date-range-picker>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button :disabled="isSending" class="btn btn-info add-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Plan Modal -->
</template>

<script>
    import Plan from "../../../models/smps/Plan";
    import {EventBus} from "../../../app";

    export default {
        created() {
            EventBus.$on('EDIT_PLAN', this.editPlan);
        },
        data() {
            return {
                isSending: false,
                plan: new Plan(),
                dateConfig: {
                    showDropdowns: true,
                    singleDatePicker: true,
                    minDate: this.$moment(), // now
                    opens: "center",
                    locale: {
                        format: 'DD MMM YYYY'
                    }
                }
            }
        },
        methods: {
            editPlan(plan) {
                if (plan) {
                    this.plan = plan;
                } else {
                    this.plan = new Plan();
                }
                $(this.$refs.planModal).modal('show');
            },
            async savePlan() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_PLAN', this.plan);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('PLAN_SAVED');
                    this.closePreview();
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.plan = new Plan();
                $(this.$refs.planModal).modal('hide');
                $(".modal-backdrop").remove();
            }
        }
    }
</script>

<style scoped>

</style>
