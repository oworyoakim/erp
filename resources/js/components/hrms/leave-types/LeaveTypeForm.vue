<template>
    <!-- Leave Type Modal -->
    <div ref="leaveTypeModal" id="leaveTypeModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Type</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveLeaveType" action="javascript:void(0);">
                        <div class="form-group">
                            <label>Title<span class="text-danger">*</span></label>
                            <input v-model="leaveType.title" class="form-control" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <label>Description<span class="text-danger">*</span></label>
                            <textarea v-model="leaveType.description" class="form-control"></textarea>
                        </div>
                        <div class="submit-section">
                            <button :disabled="!!!leaveType.title" class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Leave Type Modal -->
</template>

<script>
    import LeaveType from "../../../models/hrms/LeaveType";
    import {EventBus} from "../../../app";

    export default {
        created() {
            EventBus.$on('editLeaveType',this.editLeaveType);
        },
        data() {
            return {
                leaveType: new LeaveType(),
                isSending: true
            };
        },
        methods: {
            editLeaveType(leaveType) {
                this.leaveType = JSON.parse(JSON.stringify(leaveType));
                console.log(this.leaveType);
                $(this.$refs.leaveTypeModal).modal('show');
            },
            async saveLeaveType() {
                try {
                    if (!!this.leaveType.id) {
                        localStorage.setItem('activeLeaveType', JSON.stringify(this.leaveType));
                    }
                    let response = await this.$store.dispatch('SAVE_LEAVE_TYPE',this.leaveType);
                    EventBus.$emit('leaveTypeSaved');
                    toastr.success(response);
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    localStorage.removeItem('activeLeaveType');
                }
            },
            closePreview() {
                this.leaveType = new LeaveType();
                $(this.$refs.leaveTypeModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>
