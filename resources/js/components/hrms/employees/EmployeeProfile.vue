<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb dashboard-link="/hrms" :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="/hrms/employees" class="btn btn-dark btn-sm pull-right">
                        <i class="fa fa-list"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employee">
            <h4>Employee with username {{username}} not found!</h4>
        </template>
        <template v-else>
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
                                        <h3 class="user-name m-t-0 mb-0">{{employee.fullName}}</h3>
                                        <div class="title">
                                            <span>Employee ID :</span>
                                            <span class="text-muted">{{employee.employeeNumber}}</span>
                                        </div>
                                        <div class="title">
                                            <span>Position:</span>
                                            <span class="small text-muted">{{employee.designation.title}}</span>
                                        </div>
                                        <div class="title">
                                            <span>Date of Join :</span>
                                            <span
                                                class="small text-muted"
                                            >{{$moment(employee.joinDate).format('MMM D YYYY')}}</span>
                                        </div>
                                        <div class="title">
                                            <span>Reports to:</span>
                                            <template v-if="!!employee.supervisor">
                                                <span class="small text-muted">
                                                <a :href="`/hrms/employees/profile/${employee.supervisor.username}`"
                                                   target="__blank">
                                                    <span>{{employee.supervisor.fullName}}</span>
                                                </a>
                                                <span class="small" v-if="!!employee.supervisor.designation">({{employee.supervisor.designation.title}})</span>
                                            </span>
                                            </template>
                                            <template v-else>
                                                <span class="small text-muted">The Board</span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="personal-info">
                                        <div class="title">
                                            <span>Next Work Anniversary: </span>
                                            <span class="text-muted">
                                                <template v-if="!!employee.nextWorkAnniversary">
                                                    {{$moment(employee.nextWorkAnniversary).format("DD MMM, YYYY")}}
                                                </template>
                                            </span>
                                        </div>
                                    </div>
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
                                <a href="#employee-bank" @click="getBankInfo()" data-toggle="tab"
                                   class="nav-link">Bank</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-education" @click="getEducationInfo()" data-toggle="tab"
                                   class="nav-link">Education</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-experience" @click="getExperienceInfo()" data-toggle="tab"
                                   class="nav-link">Experience</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-related-persons" @click="getRelatedPersonsInfo()" data-toggle="tab"
                                   class="nav-link">
                                    Related Persons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-leaves" @click="getLeavesInfo()" data-toggle="tab" class="nav-link">Leaves</a>
                            </li>
                            <li class="nav-item">
                                <a href="#employee-documents" @click="getDocumentsInfo()" data-toggle="tab"
                                   class="nav-link">Documents</a>
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
                            <!--                            <PersonalInfo :employee.sync="employee"/>-->
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
                            <!--                            <ContactsInfo :contacts="employee.contacts"/>-->
                        </div>
                    </div>
                </div>
                <!-- /Employee Info Tab -->

                <!-- Banks Tab -->
                <div class="tab-pane fade" id="employee-bank">
                    <div class="row">
                        <div class="col-md-12">
                            <BankInfo :employee-id="employee.id"/>
                        </div>
                    </div>
                </div>
                <!-- /Banks Tab -->

                <!-- Education Tab -->
                <div class="tab-pane fade" id="employee-education">
                    <div class="row">
                        <div class="col-md-12">
                            <EducationInfo :employee-id="employee.id"/>
                        </div>
                    </div>
                </div>
                <!-- /Education Tab -->

                <!-- Experience Tab -->
                <div class="tab-pane fade" id="employee-experience">
                    <div class="row">
                        <div class="col-md-12">
                            <ExperienceInfo :employee-id="employee.id"/>
                        </div>
                    </div>
                </div>
                <!-- /Experience Tab -->

                <!-- Related Persons Tab -->
                <div class="tab-pane fade" id="employee-related-persons">
                    <div class="row">
                        <div class="col-md-12">
                            <RelatedPersonsInfo :employee-id="employee.id"/>
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
                                <!--                                <app-leaves-info :employee="employee"></app-leaves-info>-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3 class="card-title">Leave Applications</h3>
                                        <!--                                        <app-leave-applications-list-->
                                        <!--                                            :leave-applications="leaveApplications"></app-leave-applications-list>-->
                                        <!--                                        <app-leave-application-form-->
                                        <!--                                            :employee_id="employee.id"></app-leave-application-form>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- /Leaves Tab -->

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="employee-documents">
                    <div class="row">
                        <div class="col-md-12">
                            <EmployeeDocuments :employee="employee" title="Employee Documents"/>
                        </div>
                    </div>
                </div>
                <!-- /Documents Tab -->
                <!-- Profile Download Tab -->
                <div class="tab-pane fade" id="download-employee-profile">
                    <app-download-employee-profile :employee="employee"></app-download-employee-profile>
                </div>
                <!-- /Profile Download  Tab -->

                <!-- Employee Subordinates Tab -->
                <div class="tab-pane fade" id="employee-subordinates">
                    <div class="row staff-grid-row">
                        <div v-for="subordinate in employee.subordinates" class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                            <EmployeeProfileWidget :employee.sync="subordinate" :key="subordinate.id"/>
                        </div>
                    </div>
                </div>
                <!-- /Employee Subordinates  Tab -->

                <!-- Delegations Tab -->
                <div class="tab-pane fade" id="employee-delegations">

                </div>
                <!-- /Delegations  Tab -->
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import EducationInfo from "../education/EducationInfo";
    import ExperienceInfo from "../experience/ExperienceInfo";
    import RelatedPersonsInfo from "../related-persons/RelatedPersonsInfo";
    import PersonalInfo from "./PersonalInfo";
    import ContactsInfo from "./ContactsInfo";
    import BankInfo from "../bank/BankInfo";
    import EmployeeWidget from "./EmployeeWidget";
    import EmployeeDocuments from "../documents/EmployeeDocuments";
    import EmployeeProfileWidget from "./EmployeeProfileWidget";

    export default {
        components: {
            EmployeeWidget,
            EmployeeProfileWidget,
            BankInfo,
            ContactsInfo,
            PersonalInfo,
            RelatedPersonsInfo,
            ExperienceInfo,
            EducationInfo,
            EmployeeDocuments
        },
        props: {
            username: {type: String, required: true},
            title: String
        },
        created() {
            this.$store.dispatch("GET_FORM_SELECTIONS_OPTIONS", {});
            this.$store.dispatch("GET_LEAVE_TYPES");
            this.getEmployeeProfileData()
                .then(() => {
                    //this.getLeaveApplications();
                })
                .catch(error => {
                    console.log(error);
                });
            // EventBus.$on(
            //     ["PROFILE_INFO_SAVED", "PROFILE_PICTURE_UPLOADED"],
            //     this.getEmployeeProfileData
            // );
            // EventBus.$on(
            //     ["LEAVE_APPLICATION_SAVED", "LEAVE_APPLICATION_DELETED"],
            //     this.getLeaveApplications
            // );
        },
        computed: {
            ...mapGetters({
                employee: "ACTIVE_EMPLOYEE",
                leaveApplications: "LEAVE_APPLICATIONS",
                formSelectionOptions: "FORM_SELECTIONS_OPTIONS"
            })
        },
        data() {
            return {
                delegations: [],
                breadcrumbItems: [
                    {href: "/hrms/employees", text: "Employees", class: ""},
                    {href: "#", text: this.title, class: "active"}
                ],
                profilePhoto: null,
                isLoading: false,
                personsLoading: false,
                applicationsLoading: false,
                leavesLoading: false,
                documentsLoading: false,
                isUploading: false
            };
        },
        methods: {
            async getEmployeeProfileData() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("GET_EMPLOYEE_PROFILE", {username: this.username});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },

            getBankInfo() {
                this.$emit('LOAD_BANK_INFO');
            },

            getEducationInfo() {
                this.$emit('LOAD_EDUCATION_INFO');
            },
            getExperienceInfo() {
                this.$emit('LOAD_EXPERIENCE_INFO');
            },

            getRelatedPersonsInfo() {
                this.$emit('LOAD_RELATED_PERSONS_INFO');
            },
            async getLeaveApplicationsInfo() {
                try {
                    this.applicationsLoading = true;
                    //await this.$store.dispatch("GET_LEAVE_APPLICATIONS_INFO", {employeeId: this.employee.id});
                    this.applicationsLoading = false;
                } catch (error) {
                    console.error(error);
                    this.applicationsLoading = false;
                }
            },

            async getLeavesInfo() {
                try {
                    this.leavesLoading = true;
                    //await this.$store.dispatch("GET_RELATED_PERSONS_INFO", {employeeId: this.employee.id});
                    this.leavesLoading = false;
                } catch (error) {
                    console.error(error);
                    this.leavesLoading = false;
                }
            },

            getDocumentsInfo() {
                this.$emit('LOAD_EMPLOYEE_DOCUMENTS');
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
                    data.append("employeeId", this.employee.id);
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
            },
        }
    };
</script>

<style scoped>
    .basic-info {
        border-right: 2px dashed #ccc;
    }

    .supervisor-widget {
        font-size: 12px !important;
    }
</style>
