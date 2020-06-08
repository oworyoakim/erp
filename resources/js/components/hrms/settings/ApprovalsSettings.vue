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
        <app-spinner v-if="isLoading"></app-spinner>
        <template v-else>
            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item"><a href="#leaveApplicationsSettings" data-toggle="tab"
                                                    class="nav-link active">Leave Applications</a>
                            </li>
                            <li class="nav-item"><a href="#salaryAdvances" data-toggle="tab" class="nav-link">Salary
                                Advances</a>
                            </li>
                            <li class="nav-item"><a href="#softwareRequests" data-toggle="tab"
                                                    class="nav-link">Software Requests</a></li>
                            <li class="nav-item"><a href="#policyDocument" data-toggle="tab" class="nav-link">Policy
                                Document</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <!--Leave Applications Settings Tab -->
                <div id="leaveApplicationsSettings" class="pro-overview tab-pane fade active show">
                    <div class="card card-body table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Applicant Designation</th>
                                <th>Verified By</th>
                                <th>Approved By</th>
                                <th>Granted By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="designation in designations">
                                <td>{{designation.title}}</td>
                                <td>
                                    <template v-if="!!designation.leaveApplicationVerifier">
                                        {{designation.leaveApplicationVerifier.title}}
                                    </template>
                                </td>
                                <td>
                                    <template v-if="!!designation.leaveApplicationApprover">
                                        {{designation.leaveApplicationApprover.title}}
                                    </template>
                                </td>
                                <td>
                                    <template v-if="!!designation.leaveApplicationGranter">
                                        {{designation.leaveApplicationGranter.title}}
                                    </template>
                                </td>
                                <td>
                                    <button @click="editLeaveApplicationSetting(designation)" type="button"
                                            class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Leave Application Setting Modal -->
                    <app-main-modal title="Leave Applications Settings For"
                                    :is-open="isEditing"
                                    @modal-closed="resetForm()"
                                    key="hrms-leave-approvals-form">
                        <template v-if="!!activeDesignation" >
                            <h4 class="text-center mb-5">{{activeDesignation.title}}</h4>
                            <form @submit.prevent="saveLeaveApplicationSetting" ref="leaveApplicationSettingForm">
                                <div class="form-group row">
                                    <label class="col-sm-3">Verified By<span
                                        class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <app-select-box :select-options="designationOptions"
                                                        v-model="leaveApplicationSetting.verifiedBy"
                                                        :value="leaveApplicationSetting.verifiedBy"
                                                        placeholder="Select..."
                                                        key="leave-application-verifier"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3">Approved By<span
                                        class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <app-select-box :select-options="designationOptions"
                                                        v-model="leaveApplicationSetting.approvedBy"
                                                        :value="leaveApplicationSetting.approvedBy"
                                                        key="leave-application-approver"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3">Granted By<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <app-select-box :select-options="designationOptions"
                                                        v-model="leaveApplicationSetting.grantedBy"
                                                        :value="leaveApplicationSetting.grantedBy"
                                                        key="leave-application-granter"
                                        />
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button :disabled="formInvalid" class="btn btn-primary submit-btn btn-block">
                                        <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                                        <span v-else>Save</span>
                                    </button>
                                </div>
                            </form>
                        </template>
                    </app-main-modal>
                </div>
                <div id="salaryAdvances" class="pro-overview tab-pane fade">
                    <h3>Coming Soon</h3>
                </div>
                <div id="softwareRequests" class="pro-overview tab-pane fade">
                    <h3>Coming Soon</h3>
                </div>
                <div id="policyDocument" class="pro-overview tab-pane fade">
                    <h3>Coming Soon</h3>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import LeaveApplicationSetting from "../../../models/hrms/LeaveApplicationSetting";

    export default {
        props: ['title'],
        created() {
            this.getDesignations();
        },
        data() {
            return {
                activeDesignation: null,
                leaveApplicationSetting: new LeaveApplicationSetting(),
                breadcrumbItems: [
                    {href: '/settings', text: 'Settings', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
                isEditing: false,
                isSending: false,
            };
        },
        computed: {
            ...mapGetters({
                designations: 'DESIGNATIONS',
            }),
            designationOptions() {
                return this.designations.map((designation) => {
                    return {
                        text: designation.title,
                        value: designation.id,
                    };
                });
            },
            formInvalid() {
                return this.isSending || !(!!this.leaveApplicationSetting.designationId && !!this.leaveApplicationSetting.verifiedBy && !!this.leaveApplicationSetting.approvedBy && !!this.leaveApplicationSetting.grantedBy);
            }
        },
        methods: {
            async getDesignations() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DESIGNATIONS', {});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editLeaveApplicationSetting(designation) {
                this.activeDesignation = designation;
                this.leaveApplicationSetting.designationId = designation.id;
                if (!!designation.leaveApplicationVerifier) {
                    this.leaveApplicationSetting.verifiedBy = designation.leaveApplicationVerifier.id;
                }
                if (!!designation.leaveApplicationApprover) {
                    this.leaveApplicationSetting.approvedBy = designation.leaveApplicationApprover.id;
                } else {
                    this.leaveApplicationSetting.approvedBy = designation.supervisorId;
                }
                if (!!designation.leaveApplicationGranter) {
                    this.leaveApplicationSetting.grantedBy = designation.leaveApplicationGranter.id;
                }
                console.log(this.activeDesignation);
                this.isEditing = true;
            },
            async saveLeaveApplicationSetting() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_LEAVE_APPLICATION_SETTINGS', this.leaveApplicationSetting);
                    await this.$store.dispatch('GET_DESIGNATIONS', {});
                    this.isSending = false;
                    await swal({title: response, icon: 'success'});
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.activeDesignation = null;
                this.leaveApplicationSetting = new LeaveApplicationSetting();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        },
    }
</script>
