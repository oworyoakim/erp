<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="/employees" class="btn btn-dark btn-sm pull-right">
                        <i class="fa fa-list"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading || !!!employee" class="fa fa-spinner fa-spin fa-5x"></span>
        <div v-else>
            <div class="card mb-0">
                <div class="card-body text-lg">
                    <div class="profile-view">
                        <div class="profile-img-wrap">
                            <div class="profile-img">
                                <input
                                    type="file"
                                    accept=".png, .jpg, .jpeg, .bmp"
                                    v-on:change="profilePictureChanged($event)"
                                    ref="profilePicture"
                                    style="display: none"
                                />
                                <a @click="$refs.profilePicture.click()" href="#">
                                    <img :src="employee.avatar" alt/>
                                </a>
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-6 basic-info">
                                    <div class="personal-info">
                                        <h3 class="user-name m-t-0 mb-0">{{employee.full_name}}</h3>
                                        <div class="title">
                                            <span>Employee ID :</span>
                                            <span class="text-muted">{{employee.employee_number}}</span>
                                        </div>
                                        <div class="title">
                                            <span>Position:</span>
                                            <span class="small text-muted">{{employee.designation.title}}</span>
                                        </div>
                                        <div class="title">
                                            <span>Date of Join :</span>
                                            <span
                                                class="small text-muted"
                                            >{{$moment(employee.date_joined).format('MMM D YYYY')}}</span>
                                        </div>
                                        <div class="title row">
                                            <span class="col-4">Reports to:</span>
                                            <div class="col-8 small text-muted supervisor-widget">
                                                <app-employee-widget
                                                    v-if="!!employee.supervisor"
                                                    :username="employee.supervisor.username"
                                                    :name="employee.supervisor.full_name"
                                                    :avatar="employee.supervisor.avatar"
                                                ></app-employee-widget>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="personal-info"></div>
                                </div>
                            </div>
                        </div>
                        <div class="pro-edit">
                            <button
                                v-if="!!profilePhoto"
                                type="button"
                                :disabled="isUploading"
                                @click="uploadProfilePicture"
                                class="btn btn-info btn-xs"
                            >
                                <i class="fa fa-upload"></i> Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item">
                                <a href="#employee-info" data-toggle="tab" class="nav-link active">
                                    Personal
                                    Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-bank" data-toggle="tab" class="nav-link">Bank</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-education" data-toggle="tab" class="nav-link">Education</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-experience" data-toggle="tab" class="nav-link">Experience</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-related-persons" data-toggle="tab" class="nav-link">
                                    Related
                                    Persons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-leaves" data-toggle="tab" class="nav-link">Leaves</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-documents" data-toggle="tab" class="nav-link">Documents</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="#download-employee-profile"
                                    data-toggle="tab"
                                    class="nav-link"
                                >Download Profile</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="#employee-subordinates"
                                    data-toggle="tab"
                                    class="nav-link"
                                >Subordinates</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="#employee-delegations"
                                    data-toggle="tab"
                                    class="nav-link"
                                >Delegations</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <!-- Employee Info Tab -->
                <div id="employee-info" class="pro-overview tab-pane fade active show">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <app-personal-info :employee.sync="employee"></app-personal-info>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card card-body">
                                <h3 class="card-title">Contacts</h3>
                                <div class="row">
                                    <div class="col-4 title">Email</div>
                                    <div class="col-8 text">{{employee.email}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 title">Phone</div>
                                    <div class="col-8 text">
                                        <a href>{{employee.mobile}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <app-contacts-info :contacts="employee.contacts"></app-contacts-info>
                        </div>
                    </div>
                </div>
                <!-- /Employee Info Tab -->

                <!-- Banks Tab -->
                <div class="tab-pane fade" id="employee-bank">
                    <div class="row">
                        <div class="col-md-12">
                            <span v-if="bankLoading || !!!employee" class="fa fa-spinner fa-spin fa-5x"></span>
                            <app-bank-info
                                v-else
                                :employee_id="employee.id"
                                :banks="banks"
                                v-on:bankInfoSaved="getBankInfo"
                            ></app-bank-info>
                        </div>
                    </div>
                </div>
                <!-- /Banks Tab -->

                <!-- Education Tab -->
                <div class="tab-pane fade" id="employee-education">
                    <div class="row">
                        <div class="col-md-12">
                            <app-education-info :employee_id="employee.id"></app-education-info>
                        </div>
                    </div>
                </div>
                <!-- /Education Tab -->

                <!-- Experience Tab -->
                <div class="tab-pane fade" id="employee-experience">
                    <div class="row">
                        <div class="col-md-12">
                            <app-experience-info :employee_id="employee.id"></app-experience-info>
                        </div>
                    </div>
                </div>
                <!-- /Experience Tab -->

                <!-- Related Persons Tab -->
                <div class="tab-pane fade" id="employee-related-persons">
                    <div class="row">
                        <div class="col-md-12">
                            <app-related-persons-info :employee_id="employee.id"></app-related-persons-info>
                        </div>
                    </div>
                </div>
                <!-- /Related Persons Tab -->

                <!-- Leaves Tab -->
                <div class="tab-pane fade" id="employee-leaves">
                    <span v-if="leavesLoading" class="fa fa-spinner fa-spin fa-5x"></span>
                    <template v-else>
                        <div class="row">
                            <div class="col-md-12">
                                <app-leaves-info :employee="employee"></app-leaves-info>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3 class="card-title">Leave Applications</h3>
                                        <app-leave-applications-list
                                            :leave-applications="leaveApplications"></app-leave-applications-list>
                                        <app-leave-application-form
                                            :employee_id="employee.id"></app-leave-application-form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- /Leaves Tab -->

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="employee-documents">
                    <app-employee-documents :employee="employee" title="Employee Documents"></app-employee-documents>
                </div>
                <!-- /Documents Tab -->
                <!-- Profile Download Tab -->
                <div class="tab-pane fade" id="download-employee-profile">
                    <app-download-employee-profile :employee="employee"></app-download-employee-profile>
                </div>
                <!-- /Profile Download  Tab -->

                <!-- Employee Subordinates Tab -->
                <div class="tab-pane fade" id="employee-subordinates">

                </div>
                <!-- /Employee Subordinates  Tab -->

                <!-- Delegations Tab -->
                <div class="tab-pane fade" id="employee-delegations">

                </div>
                <!-- /Delegations  Tab -->
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            username: String,
            title: String
        },
        created() {
            this.getEmployeeProfileData()
                .then(() => {
                    this.getBankInfo();
                    this.$store.dispatch("getFormSelections", {});
                    this.$store.dispatch("GET_LEAVE_TYPES");
                    this.getLeaveApplications();
                })
                .catch(error => {
                    console.log(error);
                });
            EventBus.$on(
                ["profileInfoSaved", "profilePictureUploaded"],
                this.getEmployeeProfileData
            );
            EventBus.$on(
                ["leaveApplicationSaved", "leaveApplicationDeleted"],
                this.getLeaveApplications
            );
        },
        computed: {
            ...mapGetters({
                leaveApplications: "GET_LEAVE_APPLICATIONS",
                formSelectionOptions: "getFormSelections"
            })
        },
        data() {
            return {
                employee: null,
                banks: [],
                delegations: [],
                breadcrumbItems: [
                    {href: "/employees", text: "Employees", class: ""},
                    {href: "#", text: this.title, class: "active"}
                ],
                profilePhoto: null,
                isLoading: false,
                bankLoading: false,
                leavesLoading: false,
                isUploading: false
            };
        },
        methods: {
            ...mapActions([
                "getRelationships",
                "getTitles",
                "getGenders",
                "getMaritalStatuses",
                "getReligions",
                "getLeaveApplicationStatuses"
            ]),
            async getEmployeeProfileData() {
                try {
                    this.isLoading = true;
                    let response = await this.$http.get(
                        "/employees/get-profile-data?username=" + this.username
                    );
                    this.employee = response.data;
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error.response.data);
                    this.isLoading = false;
                }
            },

            async getBankInfo() {
                try {
                    this.bankLoading = true;
                    let response = await this.$http.get(
                        "/employees/bank?employee_id=" + this.employee.id
                    );
                    this.banks = response.data;
                    this.bankLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error.response.data);
                    this.bankLoading = false;
                }
            },

            async getLeaveApplications() {
                try {
                    this.leavesLoading = true;
                    await this.$store.dispatch("GET_LEAVE_APPLICATIONS", {
                        employee_id: this.employee.id
                    });
                    this.leavesLoading = false;
                } catch (error) {
                    console.error(error);
                    this.leavesLoading = false;
                }
            },
            async profilePictureChanged() {
                if (!this.$refs.profilePicture.files.length) {
                    this.profilePhoto = null;
                    return;
                }
                this.profilePhoto = this.$refs.profilePicture.files[0];
                console.log(this.profilePhoto);
            },

            async uploadProfilePicture() {
                try {
                    if (!!!this.profilePhoto) {
                        return;
                    }
                    let data = new FormData();
                    data.append("avatar", this.profilePhoto);
                    data.append("employee_id", this.employee.id);
                    this.isUploading = true;
                    let response = await this.$store.dispatch(
                        "UPLOAD_PROFILE_PICTURE",
                        data
                    );
                    EventBus.$emit("profilePictureUploaded");
                    this.isUploading = false;
                    this.profilePhoto = null;
                    await swal(response);
                } catch (error) {
                    this.isUploading = false;
                    this.profilePhoto = null;
                    //this.$refs.profilePicture.click = null;
                    await swal({title: error, icon: "error"});
                }
            }
        }
    };
</script>

<style scoped>
    .basic-info {
        border-right: 2px dashed #ccc;
    }
</style>
