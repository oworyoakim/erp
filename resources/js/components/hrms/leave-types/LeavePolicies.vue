<template>
    <div>
        <table class="table table-condensed table-hover table-nowrap mb-0" ref="leavePoliciesDataTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Days</th>
                <th>Earned?</th>
                <th>Weekend?</th>
                <th>C/F?</th>
                <th>Active</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="leavePolicy in leavePolicies">
                <td>{{leavePolicy.title}}</td>
                <td>{{leavePolicy.gender}}</td>
                <td>{{leavePolicy.duration}}</td>
                <td>
                    <span v-if="leavePolicy.earnedLeave">Yes</span>
                    <span v-else>No</span>
                </td>
                <td>
                    <span v-if="leavePolicy.withWeekend">Yes</span>
                    <span v-else>No</span>
                </td>
                <td>
                    <span v-if="leavePolicy.carryForward">Yes</span>
                    <span v-else>No</span>
                </td>
                <td>
                    <span v-if="leavePolicy.active">Yes</span>
                    <span v-else>No</span>
                </td>
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                           aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a @click="editLeavePolicy(leavePolicy)"
                               class="dropdown-item"
                               href="#"
                               data-toggle="modal"
                               data-target="#edit_leavetype"><i
                                class="fa fa-pencil m-r-5"></i> Edit
                            </a>
                            <a v-if="!!!leavePolicy.active"
                               @click="activateLeavePolicy(leavePolicy.id)"
                               class="dropdown-item"
                               href="javascript:void(0)">
                                <i class="fa fa-trash-o m-r-5"></i> Activate
                            </a>
                            <a v-if="!!leavePolicy.active"
                               @click="deactivateLeavePolicy(leavePolicy.id)"
                               class="dropdown-item"
                               href="javascript:void(0)">
                                <i class="fa fa-trash-o m-r-5"></i> Deactivate
                            </a>
                            <a v-if="!!leavePolicy.active"
                               @click="deleteLeavePolicy(leavePolicy.id)"
                               class="dropdown-item"
                               href="#"
                               data-toggle="modal"
                               data-target="#delete_leavetype">
                                <i class="fa fa-trash-o m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            leavePolicies: Array,
        },
        created() {

        },
        mounted() {
            this.$nextTick(() => {
                $(this.$refs.leavePoliciesDataTable).DataTable();
            });
        },
        methods: {
            editLeavePolicy(leavePolicy) {
                EventBus.$emit('editLeavePolicy', leavePolicy);
            },
            async deleteLeavePolicy(id) {
                try {
                    let isConfirm = await swal({
                        title: 'Are you sure?',
                        text: "You will delete this record!",
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Yes'
                        ],
                        closeOnClickOutside: false
                    });
                    console.log(isConfirm);
                    if (isConfirm) {
                        let response = await this.$store.dispatch('DELETE_LEAVE_POLICY', {leave_policy_id: id});
                        toastr.success(response);
                        EventBus.$emit('leavePolicyDeleted');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async activateLeavePolicy(id) {
                try {
                    let isConfirm = await swal({
                        title: 'Are you sure?',
                        text: "You will activate this policy!",
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Activate'
                        ],
                        closeOnClickOutside: false
                    });
                    console.log(isConfirm);
                    if (isConfirm) {
                        let response = await this.$store.dispatch('ACTIVATE_LEAVE_POLICY', {leave_policy_id: id});
                        toastr.success(response);
                        EventBus.$emit('leavePolicyActivated');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async deactivateLeavePolicy(id) {
                try {
                    let isConfirm = await swal({
                        title: 'Are you sure?',
                        text: "You will deactivate this policy!",
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Deactivate'
                        ],
                        closeOnClickOutside: false
                    });
                    console.log(isConfirm);
                    if (isConfirm) {
                        let response = await this.$store.dispatch('DEACTIVATE_LEAVE_POLICY', {leave_policy_id: id});
                        toastr.success(response);
                        EventBus.$emit('leavePolicyDeactivated');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        },
    }
</script>

<style scoped>

</style>
