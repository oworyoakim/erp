<template>
    <!-- Leave Type Modal -->
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="hrms-leave-type-form">
        <form @submit.prevent="saveLeaveType">
            <div class="form-group">
                <label>Title<span class="text-danger">*</span></label>
                <input v-model="leaveType.title" class="form-control" type="text" autofocus>
            </div>
            <div class="form-group">
                <div class="checkbox-inline">
                    <label>
                        <input v-model="leaveType.earnedLeave" type="checkbox">
                        Earned leave?
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Description<span class="text-danger">*</span></label>
                <textarea v-model="leaveType.description" class="form-control"></textarea>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary submit-btn btn-block">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
    <!-- /Leave Type Modal -->
</template>

<script>
    import LeaveType from "../../../models/hrms/LeaveType";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        created() {
            EventBus.$on('EDIT_LEAVE_TYPE', this.editLeaveType);
        },
        data() {
            return {
                leaveType: new LeaveType(),
                isEditing: false,
                isSending: false
            };
        },
        computed: {
            title() {
                return !!this.leaveType.id ? "Edit Leave Type" : "New Leave Type";
            },
            formInvalid() {
                return this.isSending || !(!!this.leaveType.title);
            },
        },
        methods: {
            editLeaveType(leaveType = null) {
                if (!!leaveType) {
                    this.leaveType = deepClone(leaveType);
                } else {
                    this.leaveType = new LeaveType();
                }
                this.isEditing = true;
            },
            async saveLeaveType() {
                try {
                    if (!!this.leaveType.id) {
                        localStorage.setItem('activeLeaveType', JSON.stringify(this.leaveType));
                    }
                    let response = await this.$store.dispatch('SAVE_LEAVE_TYPE', this.leaveType);
                    EventBus.$emit('LEAVE_TYPE_SAVED');
                    toastr.success(response);
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                    localStorage.removeItem('activeLeaveType');
                }
            },
            resetForm() {
                this.leaveType = new LeaveType();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        },
    }
</script>
