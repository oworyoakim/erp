<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped custom-table mb-0" ref="leaveTypesDataTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="ltype in leaveTypes">
                        <td>{{ltype.id}}</td>
                        <td>{{ltype.title}}</td>
                        <td>{{ltype.description}}</td>
                        <td>
                            <div class="dropdown action-label">
                                <a v-if="ltype.active == 1" class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   href="#" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-dot-circle-o text-success"></i> Active
                                </a>
                                <a v-if="ltype.active == 0" class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   href="#" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-dot-circle-o text-danger"></i> Inactive
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a v-if="ltype.active == 0" @click="activate(ltype.id)" href="#"
                                       class="dropdown-item"><i class="fa fa-dot-circle-o text-success"></i>
                                        Activate</a>
                                    <a v-if="ltype.active == 1" @click="deactivate(ltype.id)" href="#"
                                       class="dropdown-item"><i class="fa fa-dot-circle-o text-danger"></i>
                                        Deactivate</a>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                   aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a @click="editLeaveType(ltype)" class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leavetype"><i
                                        class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <a @click="deleteLeaveType(ltype.id)" class="dropdown-item" href="#"
                                       data-toggle="modal"
                                       data-target="#delete_leavetype"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!-- Leave Type Modal -->
                <div ref="leaveTypeModal" id="leaveTypeModal" class="modal custom-modal fade" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Leave Type</h5>
                                <button @click="closePreview()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="saveLeaveType">
                                    <div class="form-group">
                                        <label>Title<span class="text-danger">*</span></label>
                                        <input v-model="leaveType.title" class="form-control" type="text">
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
            </div>
        </div>
    </div>
</template>

<script>
    import LeaveType from "../../../models/hrms/LeaveType";
    import {mapGetters} from "vuex";

    export default {
        created() {
            this.getLeaveTypes().then(()=>{
                this.$nextTick(()=>{
                    $(this.$refs.leaveTypesDataTable).DataTable();
                });
            });
        },
        data() {
            return {
                leaveType: new LeaveType(),
                isLoading: true
            };
        },
        computed:{
            ...mapGetters({
                leaveTypes: 'GET_LEAVE_TYPES',
            }),
        },
        methods: {
            async getLeaveTypes() {
                try {
                    this.isLoading = true;
                    let response = await this.$store.dispatch('GET_LEAVE_TYPES');
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async activate(id) {
                try {
                    let response = await this.$store.dispatch('ACTIVATE_LEAVE_TYPE', {
                        leave_type_id: id
                    });
                    toastr.success(response);
                    this.getLeaveTypes();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async deactivate(id) {
                try {
                    let response = await this.$store.dispatch('DEACTIVATE_LEAVE_TYPE', {
                        leave_type_id: id
                    });
                    toastr.success(response);
                    this.getLeaveTypes();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async deleteLeaveType(id) {
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
                        let response = await this.$store.dispatch('DELETE_LEAVE_TYPE', id);
                        toastr.success(response);
                        this.getLeaveTypes();
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editLeaveType(leaveType) {
                this.leaveType = leaveType;
                console.log(this.leaveType);
                $(this.$refs.leaveTypeModal).modal('show');
            },
            async saveLeaveType(){
                try {
                    let response = await this.$store.dispatch('SAVE_LEAVE_TYPE',this.leaveType);
                    toastr.error(response);
                    this.getLeaveTypes();
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.closePreview();
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
