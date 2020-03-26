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
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-3x"></span>
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
                                            class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Leave Application Setting Modal -->
                    <div ref="leaveApplicationSettingModal" id="leaveApplicationSettingModal"
                         class="modal custom-modal fade" role="dialog" tabindex="-1"
                         data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content" v-if="!!activeDesignation">
                                <div class="modal-header">
                                    <h5 class="modal-title text-center">Leave Applications By <br/>
                                        <hr/>
                                        <span style="font-size: larger !important;">{{activeDesignation.title}}</span>
                                    </h5>
                                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form @submit.prevent="saveLeaveApplicationSetting"
                                          ref="leaveApplicationSettingForm">
                                        <div class="form-group row">
                                            <label class="col-sm-3">Verified By<span
                                                class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <app-select-box :select-options="designationOptions"
                                                                v-model="leaveApplicationSetting.verified_by"
                                                                :value="leaveApplicationSetting.verified_by"
                                                                :placeholder="'Select...'"></app-select-box>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3">Approved By<span
                                                class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <app-select-box :select-options="designationOptions"
                                                                v-model="leaveApplicationSetting.approved_by"
                                                                :value="leaveApplicationSetting.approved_by"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3">Granted By<span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <app-select-box :select-options="designationOptions"
                                                                v-model="leaveApplicationSetting.granted_by"
                                                                :value="leaveApplicationSetting.granted_by"/>
                                            </div>
                                        </div>
                                        <div class="submit-section">
                                            <button
                                                :disabled="isSending || !(!!leaveApplicationSetting.designation_id && !!leaveApplicationSetting.verified_by && !!leaveApplicationSetting.approved_by && !!leaveApplicationSetting.granted_by)"
                                                class="btn btn-primary submit-btn">Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
    import {EventBus} from "../../app";
    import LeaveApplicationSetting from "../../models/hrms/LeaveApplicationSetting";

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
                isSending: false,
            };
        },
        computed: {
            ...mapGetters({
                designations: 'GET_DESIGNATIONS',
            }),
            designationOptions() {
                return this.designations.map((designation) => {
                    return {
                        text: designation.title,
                        value: designation.id,
                    };
                });
            },
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
                this.leaveApplicationSetting.designation_id = designation.id;
                if (!!designation.leaveApplicationVerifier) {
                    this.leaveApplicationSetting.verified_by = designation.leaveApplicationVerifier.id;
                }
                if (!!designation.leaveApplicationApprover) {
                    this.leaveApplicationSetting.approved_by = designation.leaveApplicationApprover.id;
                } else {
                    this.leaveApplicationSetting.approved_by = designation.supervisor_id;
                }
                if (!!designation.leaveApplicationGranter) {
                    this.leaveApplicationSetting.granted_by = designation.leaveApplicationGranter.id;
                }
                $(this.$refs.leaveApplicationSettingModal).modal('show');
            },
            async saveLeaveApplicationSetting() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('saveApplicationSetting', this.leaveApplicationSetting);
                    await this.$store.dispatch('GET_DESIGNATIONS', {});
                    this.isSending = false;
                    await swal({title: response, icon: 'success'});
                    this.closePreview();
                } catch (error) {
                    this.isSending = false;
                    console.log(error);
                    await swal({title: error, icon: 'error'});
                }
            },
            closePreview() {
                this.activeDesignation = null;
                this.leaveApplicationSetting = new LeaveApplicationSetting();
                $(this.$refs.leaveApplicationSettingModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>
