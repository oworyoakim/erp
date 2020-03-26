<template>
    <table class="table table-striped custom-table mb-0" ref="leaveApplicationsDataTable">
        <thead>
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Next Actor</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="leaveApplication in leaveApplications">
            <td>{{leaveApplication.id}}</td>
            <td>
                <template v-if="!!leaveApplication.employee">
                    {{leaveApplication.employee.name}}
                </template>
            </td>
            <td>
                <template v-if="!!leaveApplication.leaveType">
                    {{leaveApplication.leaveType.title}}
                </template>
            </td>
            <td>{{$moment(leaveApplication.startDate,'YYYY-MM-DD').format('DD MMM YYYY')}}</td>
            <td>{{leaveApplication.duration}} days</td>
            <td>
                <div class="dropdown action-label">
                                <span v-if="leaveApplication.status === 'pending'" class="badge bg-inverse-info"><i
                                    class="fa fa-dot-circle-o"></i> {{leaveApplication.status}}</span>
                    <span v-if="leaveApplication.status === 'declined'" class="badge bg-inverse-warning"><i
                        class="fa fa-thumbs-o-down"></i> {{leaveApplication.status}}</span>
                    <span v-if="leaveApplication.status === 'approved'" class="badge bg-inverse-primary"><i
                        class="fa fa-thumbs-o-up"></i> {{leaveApplication.status}}</span>
                    <span v-if="leaveApplication.status === 'rejected'" class="badge bg-inverse-danger"><i
                        class="fa fa-times-circle-o"></i> {{leaveApplication.status}}</span>
                    <span v-if="leaveApplication.status === 'granted'" class="badge bg-inverse-success"><i
                        class="fa fa-check-circle-o"></i> {{leaveApplication.status}}</span>
                </div>
            </td>
            <td>
                <template v-if="leaveApplication.nextActor">
                    <app-employee-widget :key="leaveApplication.nextActor.username"
                                         :username="leaveApplication.nextActor.username"
                                         :name="leaveApplication.nextActor.name"
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
                        <a v-if="leaveApplication.canBeEdited"
                           @click="editLeaveApplication(leaveApplication)" class="dropdown-item" href="#"
                           data-toggle="modal" data-target="#leaveApplicationModal"><i
                            class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a v-if="leaveApplication.canBeVerified" @click="verify(leaveApplication.id)"
                           class="dropdown-item" href="#" data-toggle="modal"><i
                            class="fa fa-thumbs-up m-r-5"></i> Verify</a>
                        <a v-if="leaveApplication.canBeReturned" @click="returnApplication(leaveApplication.id)"
                           class="dropdown-item" href="#" data-toggle="modal"><i
                            class="fa fa-thumbs-down m-r-5"></i> Return</a>
                        <a v-if="leaveApplication.canBeApproved" @click="approve(leaveApplication.id)"
                           class="dropdown-item" href="#" data-toggle="modal"><i
                            class="fa fa-thumbs-up m-r-5"></i> Approve</a>
                        <a v-if="leaveApplication.canBeDeclined" @click="decline(leaveApplication.id)"
                           class="dropdown-item" href="#" data-toggle="modal"><i
                            class="fa fa-thumbs-down m-r-5"></i> Decline</a>
                        <a v-if="leaveApplication.canBeGranted" @click="grant(leaveApplication.id)"
                           class="dropdown-item" href="#" data-toggle="modal"><i
                            class="fa fa-check m-r-5"></i> Grant</a>
                        <a v-if="leaveApplication.canBeRejected" @click="reject(leaveApplication.id)"
                           class="dropdown-item" href="#" data-toggle="modal"><i
                            class="fa fa-times m-r-5"></i> Reject</a>
                        <a v-if="leaveApplication.canBeDeleted" @click="deleteLeaveApplication(leaveApplication.id)"
                           class="dropdown-item" href="#"
                           data-toggle="modal"
                           data-target="#delete_leaveapplication"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            leaveApplications: Array,
        },
        mounted() {
            this.$nextTick(() => {
                $(this.$refs.leaveApplicationsDataTable).DataTable();
            });
        },
        methods: {
            editLeaveApplication(leaveApplication) {
                EventBus.$emit('editLeaveApplication', leaveApplication);
            },
            async verify(id) {
                try {
                    let response = await this.$store.dispatch('VERIFY_LEAVE_APPLICATION', {
                        leave_application_id: id
                    });
                    toastr.success(response);
                    EventBus.$emit('leaveApplicationSaved');
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
                        leave_application_id: id,
                        comment: comment
                    });
                    toastr.success(response);
                    EventBus.$emit('leaveApplicationSaved');
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async approve(id) {
                try {
                    let response = await this.$store.dispatch('APPROVE_LEAVE_APPLICATION', {
                        leave_application_id: id
                    });
                    toastr.success(response);
                    EventBus.$emit('leaveApplicationSaved');
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
                        leave_application_id: id,
                        comment: comment
                    });
                    toastr.success(response);
                    EventBus.$emit('leaveApplicationSaved');
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
                        leave_application_id: id,
                        comment: comment
                    });
                    toastr.success(response);
                    EventBus.$emit('leaveApplicationSaved');
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async grant(id) {
                try {
                    let response = await this.$store.dispatch('GRANT_LEAVE_APPLICATION', {
                        leave_application_id: id
                    });
                    toastr.success(response);
                    EventBus.$emit('leaveApplicationSaved');
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async deleteLeaveApplication(id) {
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
                        let response = await this.$store.dispatch('DELETE_LEAVE_APPLICATION', id);
                        toastr.success(response);
                        EventBus.$emit('leaveApplicationDeleted');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        },
    }
</script>
