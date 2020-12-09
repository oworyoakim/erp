<template>
    <div class="hrms-leave-settings">
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
        <app-spinner v-if="isLoading"></app-spinner>
        <template v-else>
            <div class="row">
                <div class="col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <button class="btn btn-primary btn-block" @click="editLeaveType()"><i
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
                    <LeaveTypeForm />
                </div>
                <div class="col-sm-8 col-md-8 col-lg-9 col-xl-9">
                    <app-spinner v-if="isFetchingData" ></app-spinner>
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
                                        <button v-if="activeLeaveType.active"
                                                @click="deactivateLeaveType(activeLeaveType.id)"
                                                class="btn btn-danger btn-sm">
                                            <i
                                                class="fa fa-lock m-r-5"></i> Deactivate
                                        </button>
                                        <button v-if="!activeLeaveType.active"
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
                                <button class="btn add-btn btn-sm" title="Add Custom Policy" @click="editLeavePolicy()"><i class="fa fa-plus"></i> Add Custom Policy
                                </button>
                            </div>
                            <LeavePolicies :leave-policies="leavePolicies"/>
                            <LeavePolicyForm :leave-type-id.sync="activeLeaveType.id"/>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import LeaveSetting from "../../../models/hrms/LeaveSetting";
    import LeaveTypeForm from "../leave-types/LeaveTypeForm";
    import LeavePolicies from "../leave-types/LeavePolicies";
    import LeavePolicyForm from "../leave-types/LeavePolicyForm";

    export default {
        props: ['title'],
        components:{
            LeavePolicyForm,
            LeavePolicies,
            LeaveTypeForm,
        },
        created() {
            this.getLeaveTypes().then(() => {
                this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {});
            });
            EventBus.$on(['LEAVE_TYPE_SAVED','LEAVE_TYPE_DELETED'], this.getLeaveTypes);
            EventBus.$on([
                'LEAVE_POLICY_SAVED',
                'LEAVE_POLICY_DELETED'
            ], this.getLeavePolicies);
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
                leaveTypes: 'LEAVE_TYPES'
            }),
        },
        methods: {
            editLeaveType(leaveType = null) {
                EventBus.$emit('EDIT_LEAVE_TYPE', leaveType);
            },
            editLeavePolicy(leavePolicy = null) {
                EventBus.$emit('EDIT_LEAVE_POLICY', leavePolicy);
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
                        this.leavePolicies = await this.$store.dispatch('GET_LEAVE_POLICIES', {leaveTypeId: this.activeLeaveType.id});
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
                        let response = await this.$store.dispatch('ACTIVATE_LEAVE_TYPE', {
                            leaveTypeId: id
                        });
                        toastr.success(response);
                        EventBus.$emit("LEAVE_TYPE_SAVED");
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async deactivateLeaveType(id) {
                try {
                    let isConfirmed = await this.confirmAction("You will deactivate this leave!");
                    if (isConfirmed) {
                        localStorage.setItem('activeLeaveType', JSON.stringify(this.activeLeaveType));
                        this.activeLeaveType = {};
                        let response = await this.$store.dispatch('DEACTIVATE_LEAVE_TYPE', {
                            leaveTypeId: id
                        });
                        toastr.success(response);
                        EventBus.$emit("LEAVE_TYPE_SAVED");
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async deleteLeaveType(id) {
                try {
                    let isConfirmed = await this.confirmAction("You will delete this leave!");
                    if (isConfirmed) {
                        let response = await this.$store.dispatch('DELETE_LEAVE_TYPE',{leaveTypeId: id});
                        toastr.success(response);
                        EventBus.$emit("LEAVE_TYPE_DELETED");
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
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
