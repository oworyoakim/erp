<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="experience-form">
        <form @submit.prevent="saveLeaveApplication">
            <div class="form-group row">
                <label class="col-md-4">Leave Type<span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <select v-model="leaveApplication.leaveTypeId" class="form-control">
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
                                           v-model="leaveApplication.startDate"
                                           :value="leaveApplication.startDate">
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
                <button :disabled="formInvalid" class="btn btn-primary submit-btn pull-right">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Apply</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import LeaveApplication from "../../../models/hrms/LeaveApplication";

    export default {
        props: {employeeId: {type: Number, required: true}},
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            leaveTypes() {
                return this.formSelectionOptions.leaveTypes;
            },
            title(){
                return (!!this.leaveApplication.id) ? "Edit Leave Application" : "New Leave Application";
            },
            formInvalid(){
                return this.isSending || !(!!this.leaveApplication.leaveTypeId && !!this.leaveApplication.startDate && !!this.leaveApplication.duration && !!this.leaveApplication.comment);
            },
        },
        created() {
            EventBus.$on('EDIT_LEAVE_APPLICATION', this.editLeaveApplication);
        },
        mounted() {
            this.leaveApplication.employeeId = this.employeeId || null;
        },
        data() {
            return {
                leaveApplication: new LeaveApplication(),
                startDateConfig: {
                    showDropdowns: true,
                    singleDatePicker: true,
                    minDate: this.$moment().add(1, 'days'), // today
                    opens: "center",
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                },
                isSending: false,
                isEditing: false
            };
        },
        methods: {
            editLeaveApplication(leaveApplication = null) {
                if (!!leaveApplication) {
                    this.leaveApplication = leaveApplication;
                } else {
                    this.leaveApplication = new LeaveApplication();
                }
                this.isEditing = true;
            },
            async saveLeaveApplication() {
                try {
                    this.isSending = true;
                    this.leaveApplication.employeeId = this.employeeId || null;
                    let response = await this.$store.dispatch('SAVE_LEAVE_APPLICATION', this.leaveApplication);
                    toastr.success(response);
                    EventBus.$emit('LEAVE_APPLICATION_SAVED');
                    this.resetForm();
                    this.isSending = false;
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.leaveApplication = new LeaveApplication();
                this.isEditing = false;
                $(".modal-backdrop").remove();
            },
        },
    }
</script>
