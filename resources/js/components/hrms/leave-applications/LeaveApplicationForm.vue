<template>
    <!-- LeaveApplication Modal -->
    <div ref="leaveApplicationModal" id="leaveApplicationModal" class="modal custom-modal fade"
         role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Application</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveLeaveApplication">
                        <div class="form-group row">
                            <label class="col-md-4">Leave Type<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select v-model="leaveApplication.leave_type_id" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="leaveType in leaveTypes" :value="leaveType.id">
                                        {{leaveType.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Start Date<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <app-date-range-picker :config="startDateConfig"
                                                v-model="leaveApplication.start_date"
                                                :value="leaveApplication.start_date">
                                </app-date-range-picker>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Duration (in days)<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input v-model="leaveApplication.duration" class="form-control" type="number"
                                       min="1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Comment<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea v-model="leaveApplication.comment" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button
                                :disabled="isSending || !(!!leaveApplication.leave_type_id && !!leaveApplication.start_date && !!leaveApplication.duration && !!leaveApplication.comment)"
                                class="btn btn-primary submit-btn pull-right">Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- / LeaveApplication Modal -->
</template>

<script>
    import LeaveApplication from "../../../models/hrms/LeaveApplication";
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        props:{
            employee_id: Number,
        },
        computed:{
            ...mapGetters({
                leaveApplicationStatuses: 'getLeaveApplicationStatuses',
                leaveTypes: 'GET_LEAVE_TYPES',
            }),
        },
        created(){
            EventBus.$on('editLeaveApplication',this.editLeaveApplication);
        },
        mounted(){
            this.leaveApplication.employee_id = this.employee_id || null;
        },
        data() {
            return {
                leaveApplication: new LeaveApplication(),
                startDateConfig: {
                    showDropdowns: true,
                    singleDatePicker: true,
                    minDate: this.$moment().add(1,'days'), // today
                    opens: "center",
                    locale: {
                        format: 'DD MMM YYYY'
                    }
                },
                isSending: false
            };
        },
        methods: {
            editLeaveApplication(leaveApplication) {
                this.leaveApplication = leaveApplication;
                console.log(this.leaveApplication);
                $(this.$refs.leaveApplicationModal).modal('show');
            },
            async saveLeaveApplication() {
                try {
                    this.isSending = true;
                    this.leaveApplication.employee_id = this.employee_id || null;
                    let response = await this.$store.dispatch('SAVE_LEAVE_APPLICATION',this.leaveApplication);
                    toastr.success(response);
                    EventBus.$emit('leaveApplicationSaved');
                    this.closePreview();
                    this.isSending = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isSending = false;
                }
            },
            closePreview() {
                this.leaveApplication = new LeaveApplication();
                $(this.$refs.leaveApplicationModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>
