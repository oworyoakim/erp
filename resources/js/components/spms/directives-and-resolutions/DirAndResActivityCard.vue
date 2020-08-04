<template>
    <div v-bind:class="`card flex-fill ${activityStatus(activity.status)}`">
        <div v-bind:class="`card-header ${activityStatus(activity.status)}`">
            <h5 class="card-title mb-0">{{activity.title}}</h5>
        </div>
        <div class="card-body">
            <p class="card-text task-date">
                <span v-if="!!activity.endDate">Completed on: {{activity.endDate}}</span>
                <span v-else>Due on: {{activity.dueDate}}</span>
                <span class="badge badge-secondary pull-right">{{activity.status}}</span>
            </p>
            <div class="card-text">
                <a v-if="activity.canBeApproved && $store.getters.HAS_ACCESS('activities.approve')"
                   :disabled="isSending" @click="updateActivity('approve',activity)"
                   class="btn btn-success btn-sm" href="javascript:void(0);">Approve</a>
                <a v-if="activity.canBeDeclined && $store.getters.HAS_ACCESS('activities.decline')"
                   :disabled="isSending" @click="updateActivity('decline',activity)"
                   class="btn btn-primary btn-sm" href="javascript:void(0);">Decline</a>
                <a v-if="activity.canBeStarted && $store.getters.HAS_ACCESS('activities.start')" :disabled="isSending"
                   @click="updateActivity('start',activity)"
                   class="btn btn-primary  btn-sm"
                   href="javascript:void(0);">Start</a>
                <a v-if="activity.canBeHeld && $store.getters.HAS_ACCESS('activities.hold')" :disabled="isSending"
                   @click="updateActivity('hold',activity)"
                   class="btn btn-primary  btn-sm"
                   href="javascript:void(0);">Hold</a>
                <a v-if="activity.canBeResumed && $store.getters.HAS_ACCESS('activities.hold')" :disabled="isSending"
                   @click="updateActivity('resume',activity)"
                   class="btn btn-warning  btn-sm"
                   href="javascript:void(0);">Resume</a>
                <template v-if="activity.outputs.length > 0">
                    <a v-if="activity.canBeCompleted && $store.getters.HAS_ACCESS('activities.complete')"
                       :disabled="isSending" @click="completeActivity(activity)"
                       class="btn btn-success btn-sm" href="javascript:void(0);">Complete</a>
                </template>
                <template v-else>
                    <a v-if="activity.canBeCompleted && $store.getters.HAS_ACCESS('activities.complete')"
                       :disabled="isSending" @click="updateActivity('complete',activity)"
                       class="btn btn-success btn-sm" href="javascript:void(0);">Complete</a>
                </template>
                <a v-if="activity.canBeEdited && $store.getters.HAS_ACCESS('activities.update')" :disabled="isSending"
                   @click="editActivity(activity)"
                   class="btn btn-info btn-sm" href="javascript:void(0);">Edit</a>
            </div>
        </div>
    </div>
</template>

<script>
    import {displayErrorMessage} from "../../../utils/helpers";
    import {EventBus} from "../../../app";

    export default {
        props: {
            activity: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {
                isSending: false,
            }
        },
        methods: {
            editActivity(activity = null) {
                EventBus.$emit("EDIT_DIR_AND_RES_ACTIVITY", activity);
            },
            activityStatus(status) {
                if (status === 'onhold') {
                    return 'bg-inverse-purple';
                }
                if (status === 'ongoing') {
                    return 'bg-inverse-warning';
                }
                if (status === 'completed') {
                    return 'bg-inverse-success';
                }
                return 'bg-inverse-danger';
            },
            async updateActivity(action, activity) {
                try {
                    this.isSending = true;
                    let warning = '';
                    if (action === 'hold') {
                        warning = "You will put this activity on hold!";
                    } else if (action === 'approve') {
                        warning = "You will approve this activity!";
                    } else if (action === 'decline') {
                        warning = "You will decline this activity!";
                    } else if (action === 'start') {
                        warning = "You will mark this activity as started!";
                    } else if (action === 'complete') {
                        warning = "You will mark this activity as completed!";
                    } else if (action === 'resume') {
                        action = 'hold'; // we use the same action name for both hold and resume
                        warning = "You will resume execution of this activity!";
                    }
                    let isConfirmed = await swal({
                        title: 'Are you sure?',
                        text: warning,
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Yes'
                        ],
                        closeOnClickOutside: false
                    });
                    if (isConfirmed) {
                        let payload = {...activity, action};
                        let response = await this.$store.dispatch("UPDATE_DIR_AND_RES_ACTIVITY", payload);
                        toastr.success(response);
                    }
                    this.isSending = false;
                } catch (error) {
                    await displayErrorMessage(error);
                    this.isSending = false;
                }
            },
            completeActivity(activity) {
                EventBus.$emit("COMPLETE_ACTIVITY", activity);
            },
        }
    }
</script>

<style scoped>

</style>
