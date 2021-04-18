<template>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-striped custom-table mb-0" ref="leaveApplicationsDataTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th v-if="!employeeId">Employee</th>
                    <th>Leave Type</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Next Actor</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(leaveApplication,index) in leaveApplications">
                    <td>{{ index + 1 }}</td>
                    <td v-if="!employeeId">
                        <template v-if="!!leaveApplication.employee">
                            {{ leaveApplication.employee.name }}
                        </template>
                    </td>
                    <td>
                        <template v-if="!!leaveApplication.leaveType">
                            {{ leaveApplication.leaveType.title }}
                        </template>
                    </td>
                    <td>{{ $moment(leaveApplication.startDate).format('DD MMM YYYY') }}</td>
                    <td>{{ $moment(leaveApplication.endDate).format('DD MMM YYYY') }}</td>
                    <td>{{ leaveApplication.duration }} days</td>
                    <td>
                        <div class="dropdown action-label">
                                <span v-if="leaveApplication.status === 'pending'" class="badge bg-inverse-info"><i
                                    class="fa fa-dot-circle-o"></i> {{ leaveApplication.status }}</span>
                            <span v-if="leaveApplication.status === 'verified'" class="badge bg-inverse-info"><i
                                class="fa fa-thumbs-o-up"></i> {{ leaveApplication.status }}</span>
                            <span v-if="leaveApplication.status === 'declined'" class="badge bg-inverse-warning"><i
                                class="fa fa-thumbs-o-down"></i> {{ leaveApplication.status }}</span>
                            <span v-if="leaveApplication.status === 'approved'" class="badge bg-inverse-primary"><i
                                class="fa fa-thumbs-o-up"></i> {{ leaveApplication.status }}</span>
                            <span v-if="leaveApplication.status === 'rejected'" class="badge bg-inverse-danger"><i
                                class="fa fa-times-circle-o"></i> {{ leaveApplication.status }}</span>
                            <span v-if="leaveApplication.status === 'granted'" class="badge bg-inverse-success"><i
                                class="fa fa-check-circle-o"></i> {{ leaveApplication.status }}</span>
                        </div>
                    </td>
                    <td>
                        <template v-if="leaveApplication.nextActor">
                            <app-employee-widget :key="leaveApplication.nextActor.username"
                                                 :username="leaveApplication.nextActor.username"
                                                 :name="leaveApplication.nextActor.fullName"
                                                 :avatar="leaveApplication.nextActor.avatar"
                                                 :position="leaveApplication.nextActor.designation.title">
                            </app-employee-widget>
                        </template>
                    </td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a v-if="canBeViewed(leaveApplication)"
                                   @click="viewLeaveHistory(leaveApplication)" class="dropdown-item" href="#"
                                   data-toggle="modal" data-target="#leaveHistoryModal"><i
                                    class="fa fa-eye m-r-5"></i> View History</a>
                                <a v-if="canBeEdited(leaveApplication)"
                                   @click="editLeaveApplication(leaveApplication)" class="dropdown-item" href="#"
                                   data-toggle="modal" data-target="#leaveApplicationModal"><i
                                    class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a v-if="canBeVerified(leaveApplication)" @click="verify(leaveApplication.id)"
                                   class="dropdown-item" href="#" data-toggle="modal"><i
                                    class="fa fa-thumbs-up m-r-5"></i> Verify</a>
                                <a v-if="canBeReturned(leaveApplication)"
                                   @click="returnApplication(leaveApplication.id)"
                                   class="dropdown-item" href="#" data-toggle="modal"><i
                                    class="fa fa-thumbs-down m-r-5"></i> Return</a>
                                <a v-if="canBeApproved(leaveApplication)" @click="approve(leaveApplication.id)"
                                   class="dropdown-item" href="#" data-toggle="modal"><i
                                    class="fa fa-thumbs-up m-r-5"></i> Approve</a>
                                <a v-if="canBeDeclined(leaveApplication)" @click="decline(leaveApplication.id)"
                                   class="dropdown-item" href="#" data-toggle="modal"><i
                                    class="fa fa-thumbs-down m-r-5"></i> Decline</a>
                                <a v-if="canBeGranted(leaveApplication)" @click="grant(leaveApplication.id)"
                                   class="dropdown-item" href="#" data-toggle="modal"><i
                                    class="fa fa-check m-r-5"></i> Grant</a>
                                <a v-if="canBeRejected(leaveApplication)" @click="reject(leaveApplication.id)"
                                   class="dropdown-item" href="#" data-toggle="modal"><i
                                    class="fa fa-times m-r-5"></i> Reject</a>
                                <a v-if="canBeDeleted(leaveApplication)"
                                   @click="deleteLeaveApplication(leaveApplication)"
                                   class="dropdown-item" href="#"
                                   data-toggle="modal"
                                   data-target="#delete_leaveapplication"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <StaticMainModal v-if="!!leaveHistory" size="md" title="Employee Leave History"
                             @modal-closed="leaveHistory = null;">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th>Full Name</th>
                        <td>{{ leaveHistory.fullName }}</td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td>{{ leaveHistory.designation }}</td>
                    </tr>
                    <tr>
                        <th>Directorate</th>
                        <td>{{ leaveHistory.directorate }}</td>
                    </tr>
                    <tr>
                        <th>Supervisor</th>
                        <td>
                            <template v-if="leaveHistory.supervisor">
                                {{ leaveHistory.supervisor.fullName }}
                            </template>
                        </td>
                    </tr>
                    <tr>
                        <th>Last Work Anniversary</th>
                        <td>{{ leaveHistory.lastWorkAnniversary }}</td>
                    </tr>
                    <tr>
                        <th>Next Work Anniversary</th>
                        <td>{{ leaveHistory.nextWorkAnniversary }}</td>
                    </tr>
                    <tr>
                        <th>Salary Scale</th>
                        <td>{{ leaveHistory.salaryScale }}</td>
                    </tr>
                    <tr>
                        <th>Last Day On Leave</th>
                        <td>{{ leaveHistory.lastDayOnLeave }}</td>
                    </tr>
                    <tr>
                        <th>Leave Requested</th>
                        <td>{{ leaveHistory.leaveType }}</td>
                    </tr>
                    <tr>
                        <th>Totals Days Earned</th>
                        <td>{{ leaveHistory.daysEarned }}</td>
                    </tr>
                    <tr>
                        <th>Totals Days Accumulated</th>
                        <td>{{ leaveHistory.totalDaysAccumulated }}</td>
                    </tr>
                    <tr>
                        <th>Entitlement</th>
                        <td>{{ leaveHistory.entitlement }} days</td>
                    </tr>
                </table>
            </StaticMainModal>
        </div>
    </div>
</template>

<script>
import {EventBus} from "../../../app";
import {mapGetters} from "vuex";
import StaticMainModal from "../../shared/StaticMainModal";

export default {
    components: {StaticMainModal},
    props: {
        leaveApplications: {type: Array, default: () => []},
        employeeId: Number,
    },
    mounted() {

    },
    data() {
        return {
            leaveHistory: null,
        }
    },
    computed: {
        ...mapGetters({
            loggedInUser: 'getUser',
        }),
        canBeVerified() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let employeeData = (this.loggedInUser) ? this.loggedInUser.employeeData : null;
                if (!employeeData || !leaveApplication.employee) {
                    return false;
                }
                let isVerifier = employeeData.verifiableLeaveApplicants.includes(leaveApplication.employee.designationId);
                let hasVerifyPermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.verify']);
                return leaveApplication.canBeVerified && isVerifier && hasVerifyPermission;
            }
        },
        canBeReturned() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let employeeData = (this.loggedInUser) ? this.loggedInUser.employeeData : null;
                if (!employeeData || !leaveApplication.employee) {
                    return false;
                }
                let isVerifier = employeeData.verifiableLeaveApplicants.includes(leaveApplication.employee.designationId);
                let hasReturnPermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.return']);
                return leaveApplication.canBeReturned && isVerifier && hasReturnPermission;
            }
        },
        canBeApproved() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let employeeData = (this.loggedInUser) ? this.loggedInUser.employeeData : null;
                if (!employeeData || !leaveApplication.employee) {
                    return false;
                }
                let isApprover = employeeData.approvableLeaveApplicants.includes(leaveApplication.employee.designationId);
                let hasApprovePermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.approve']);
                return leaveApplication.canBeApproved && isApprover && hasApprovePermission;
            }
        },
        canBeDeclined() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let employeeData = (this.loggedInUser) ? this.loggedInUser.employeeData : null;
                if (!employeeData || !leaveApplication.employee) {
                    return false;
                }
                let isApprover = employeeData.approvableLeaveApplicants.includes(leaveApplication.employee.designationId);
                let hasDeclinePermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.decline']);
                return leaveApplication.canBeDeclined && isApprover && hasDeclinePermission;
            }
        },
        canBeGranted() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let employeeData = (this.loggedInUser) ? this.loggedInUser.employeeData : null;
                if (!employeeData || !leaveApplication.employee) {
                    return false;
                }
                let isGrantor = employeeData.grantableLeaveApplicants.includes(leaveApplication.employee.designationId);
                let hasGrantPermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.grant']);
                return leaveApplication.canBeGranted && isGrantor && hasGrantPermission;
            }
        },
        canBeRejected() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let employeeData = (this.loggedInUser) ? this.loggedInUser.employeeData : null;
                if (!employeeData || !leaveApplication.employee) {
                    return false;
                }
                let isGrantor = employeeData.grantableLeaveApplicants.includes(leaveApplication.employee.designationId);
                let hasRejectPermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.reject']);
                return leaveApplication.canBeRejected && isGrantor && hasRejectPermission;
            }
        },
        canBeEdited() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let hasEditPermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.edit']);
                return leaveApplication.canBeEdited && hasEditPermission;
            }
        },
        canBeDeleted() {
            return (leaveApplication) => {
                if (!leaveApplication) {
                    return false;
                }
                let hasDeletePermission = this.$store.getters.HAS_ANY_ACCESS(['leaves.applications.delete']);
                return leaveApplication.canBeDeleted && hasDeletePermission;
            }
        },
        canBeViewed() {
            return (leaveApplication) => {
                return this.canBeVerified(leaveApplication) ||
                    this.canBeReturned(leaveApplication) ||
                    this.canBeApproved(leaveApplication) ||
                    this.canBeDeclined(leaveApplication) ||
                    this.canBeGranted(leaveApplication) ||
                    this.canBeRejected(leaveApplication);

            }
        }
    },
    methods: {
        editLeaveApplication(leaveApplication = null) {
            EventBus.$emit('EDIT_LEAVE_APPLICATION', leaveApplication);
        },
        async verify(id) {
            try {
                let response = await this.$store.dispatch('VERIFY_LEAVE_APPLICATION', {
                    leaveApplicationId: id
                });
                toastr.success(response);
                EventBus.$emit('LEAVE_APPLICATION_SAVED');
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
        async returnApplication(id) {
            try {
                let confirmed = await swal({
                    title: 'Are you sure?',
                    content: {
                        element: "textarea",
                        attributes: {
                            id: 'comment',
                            placeholder: "Enter reason (this reason is important)",
                            required: "required",
                            rows: 3,
                        }
                    },
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: ['Cancel', 'Return'],
                });
                if (!confirmed) {
                    return;
                }
                let comment = $('#comment').val();
                let response = await this.$store.dispatch('RETURN_LEAVE_APPLICATION', {
                    leaveApplicationId: id,
                    comment: comment
                });
                toastr.success(response);
                EventBus.$emit('LEAVE_APPLICATION_SAVED');
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
        async approve(id) {
            try {
                let response = await this.$store.dispatch('APPROVE_LEAVE_APPLICATION', {
                    leaveApplicationId: id
                });
                toastr.success(response);
                EventBus.$emit('LEAVE_APPLICATION_SAVED');
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
        async decline(id) {
            try {
                let confirmed = await swal({
                    title: 'Are you sure?',
                    content: {
                        element: "textarea",
                        attributes: {
                            id: 'comment',
                            placeholder: "Enter reason (this reason is important)",
                            required: "required",
                            rows: 3,
                        }
                    },
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: ['Cancel', 'Decline'],
                });
                if (!confirmed) {
                    return;
                }
                let comment = $('#comment').val();
                let response = await this.$store.dispatch('DECLINE_LEAVE_APPLICATION', {
                    leaveApplicationId: id,
                    comment: comment
                });
                toastr.success(response);
                EventBus.$emit('LEAVE_APPLICATION_SAVED');
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
        async reject(id) {
            try {
                let confirmed = await swal({
                    title: 'Are you sure?',
                    content: {
                        element: "textarea",
                        attributes: {
                            id: 'comment',
                            placeholder: "Enter reason (this reason is important)",
                            required: "required",
                            rows: 3,
                        }
                    },
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: ['Cancel', 'Reject'],
                });
                if (!confirmed) {
                    return;
                }
                let comment = $('#comment').val();
                let response = await this.$store.dispatch('REJECT_LEAVE_APPLICATION', {
                    leaveApplicationId: id,
                    comment: comment
                });
                toastr.success(response);
                EventBus.$emit('LEAVE_APPLICATION_SAVED');
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
        async grant(id) {
            try {
                let response = await this.$store.dispatch('GRANT_LEAVE_APPLICATION', {
                    leaveApplicationId: id
                });
                toastr.success(response);
                EventBus.$emit('LEAVE_APPLICATION_SAVED');
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
        async deleteLeaveApplication(leaveApplication) {
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
                    let response = await this.$store.dispatch('DELETE_LEAVE_APPLICATION', leaveApplication.id);
                    toastr.success(response);
                    EventBus.$emit('LEAVE_APPLICATION_DELETED');
                }
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
        async viewLeaveHistory(leaveApplication) {
            try {
                this.leaveHistory = await this.$store.dispatch('GET_LEAVE_HISTORY', {
                    leaveApplicationId: leaveApplication.id,
                    employeeId: leaveApplication.employee.id
                });
            } catch (error) {
                console.log(error);
                toastr.error(error);
            }
        },
    },
}
</script>
