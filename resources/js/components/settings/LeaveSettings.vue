<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">

                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
        <div v-else class="row">
            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-3">
                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#leaveTypeModal"><i
                    class="fa fa-plus"></i> Add Leave Type
                </button>
                <div class="roles-menu">
                    <ul>
                        <li v-for="leaveType in leaveTypes"
                            v-bind:class="{active: !!activeLeaveType.id && leaveType.id === activeLeaveType.id}"><a
                            href="javascript:void(0);"
                            @click="editLeaveTypeOptions(leaveType)">{{leaveType.title}}</a>
                        </li>
                    </ul>
                </div>
                <app-leave-type-form></app-leave-type-form>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-9 col-xl-9">
                <span v-if="isFetchingData" class="fa fa-spinner fa-spin fa-5x"></span>
                <template v-else-if="!!activeLeaveType.id">
                    <div class="m-b-30">
                        <ul class="list-group notification-list">
                            <li class="list-group-item">
                                Leave Name:
                                <div class="status-toggle">
                                    {{activeLeaveType.title}}
                                </div>
                            </li>
                            <li class="list-group-item">
                                Description:
                                <div class="status-toggle">
                                    {{activeLeaveType.description}}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="status-toggle">
                                    <button @click="editLeaveType(activeLeaveType)" class="btn btn-outline-info btn-sm">
                                        <i
                                            class="fa fa-pencil m-r-5"></i> Edit
                                    </button>
                                    <button v-if="activeLeaveType.active == 1"
                                            @click="deactivateLeaveType(activeLeaveType.id)"
                                            class="btn btn-danger btn-sm">
                                        <i
                                            class="fa fa-lock m-r-5"></i> Deactivate
                                    </button>
                                    <button v-if="activeLeaveType.active == 0"
                                            @click="activateLeaveType(activeLeaveType.id)"
                                            class="btn btn-success btn-sm">
                                        <i
                                            class="fa fa-unlock m-r-5"></i> Activate
                                    </button>
                                    <button @click="deleteLeaveType(activeLeaveType.id)"
                                            class="btn btn-outline-danger btn-sm"><i
                                        class="fa fa-trash-o m-r-5"></i> Delete
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- Custom Policy -->
                    <div class="card card-body table-responsive">
                        <div class="h3 card-title">
                            Custom Policies
                            <button class="btn add-btn btn-sm" title="Add Custom Policy" data-toggle="modal"
                                    data-target="#leavePolicyModal"><i class="fa fa-plus"></i> Add Custom Policy
                            </button>
                        </div>
                        <app-leave-policies :leave-policies="leavePolicies"></app-leave-policies>
                        <app-leave-policy-form :leave-type-id.sync="activeLeaveType.id"></app-leave-policy-form>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import LeaveSetting from "../../models/LeaveSetting";
    import {EventBus} from "../../app";

    export default {
        props: ['title'],
        created() {
            this.getLeaveTypes().then(() => {
                this.$store.dispatch('getFormSelections', {});
                this.$store.dispatch('getSalaryScales');
            });
            EventBus.$on(['leaveTypeSaved'], this.getLeaveTypes);
            EventBus.$on(['leavePolicySaved','leavePolicyActivated','leavePolicyDeactivated', 'leavePolicyDeleted'], this.getLeavePolicies);
        },
        data() {
            return {
                activeLeaveType: {},
                leavePolicies: [],
                breadcrumbItems: [
                    {href: '/settings', text: 'Settings', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
                isFetchingData: false,
                isSending: false,
            };
        },
        computed: {
            ...mapGetters({
                leaveTypes: 'GET_LEAVE_TYPES'
            }),
        },
        methods: {
            editLeaveType(leaveType) {
                EventBus.$emit('editLeaveType', leaveType);
            },
            async editLeaveTypeOptions(leaveType) {
                try {
                    this.isFetchingData = true;
                    this.activeLeaveType = {};
                    this.activeLeaveType = JSON.parse(JSON.stringify(leaveType));
                    if (!!!this.activeLeaveType.setting) {
                        this.activeLeaveType.setting = new LeaveSetting();
                    }
                    await this.getLeavePolicies();
                    this.isFetchingData = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isFetchingData = false;
                }
            },
            async getLeaveTypes() {
                try {
                    this.isLoading = true;
                    this.activeLeaveType =  {};
                    let activeLeaveType = JSON.parse(localStorage.getItem('activeLeaveType'));
                    localStorage.removeItem('activeLeaveType');
                    await this.$store.dispatch('GET_LEAVE_TYPES');
                    this.isLoading = false;
                    if (!!activeLeaveType && !!activeLeaveType.id) {
                        let ltype = this.leaveTypes.find((leaveType) => {
                            return leaveType.id == activeLeaveType.id;
                        });
                        if (!!ltype) {
                            this.editLeaveTypeOptions(ltype);
                        }
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },

            async getLeavePolicies() {
                try {
                    if (!!this.activeLeaveType.id) {
                        this.leavePolicies = await this.$store.dispatch('GET_LEAVE_POLICIES', {leave_type_id: this.activeLeaveType.id});
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },

            async activateLeaveType(id) {
                try {
                    let isConfirmed = await this.confirmAction("You will activate this leave!");
                    if (isConfirmed) {
                        localStorage.setItem('activeLeaveType', JSON.stringify(this.activeLeaveType));
                        this.activeLeaveType = {};
                        let response = await this.$http.patch('/leaves/types/activate', {
                            leave_type_id: id
                        });
                        toastr.success(response.data);
                        this.getLeaveTypes();
                    }
                } catch (error) {
                    console.log(error.response.data);
                    toastr.error(error.response.data);
                }
            },
            async deactivateLeaveType(id) {
                try {
                    let isConfirmed = await this.confirmAction("You will deactivate this leave!");
                    if (isConfirmed) {
                        localStorage.setItem('activeLeaveType', JSON.stringify(this.activeLeaveType));
                        this.activeLeaveType = {};
                        let response = await this.$http.patch('/leaves/types/deactivate', {
                            leave_type_id: id
                        });
                        toastr.success(response.data);
                        this.getLeaveTypes();
                    }
                } catch (error) {
                    console.log(error.response.data);
                    toastr.error(error.response.data);
                }
            },
            async deleteLeaveType(id) {
                try {
                    let isConfirmed = await this.confirmAction("You will delete this leave!");
                    if (isConfirmed) {
                        let response = await this.$http.delete('/leaves/types/delete?leave_type_id=' + id);
                        toastr.success(response.data);
                        this.getLeaveTypes();
                    }
                } catch (error) {
                    console.log(error.response.data);
                    toastr.error(error.response.data);
                }
            },
            async confirmAction(actionText) {
                let isConfirm = await swal({
                    title: 'Are you sure?',
                    text: actionText,
                    icon: 'warning',
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    closeOnClickOutside: false
                });
                return !!isConfirm;
            }
        },
    }
</script>
