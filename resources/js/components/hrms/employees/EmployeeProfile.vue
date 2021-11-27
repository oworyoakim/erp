<template>
    <div class="mt-0">
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
            <h4>Employee with username {{ username }} not found!</h4>
        </template>
        <template v-else>
            <div class="card mb-2">
                <div class="card-body text-lg">
                    <div class="profile-view">
                        <div class="profile-img-wrap h-auto">
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
                            <button v-if="!!profilePhoto"
                                    type="button"
                                    :disabled="isUploading"
                                    @click="uploadProfilePicture"
                                    class="btn btn-info btn-sm btn-block mt-2">
                                <i class="fa fa-upload"></i> Upload
                            </button>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-6 basic-info">
                                    <div class="personal-info">
                                        <h3 class="user-name m-t-0 mb-0">{{ employee.fullName }}</h3>
                                        <div class="title">
                                            <span>Employee ID :</span>
                                            <span class="text-muted">{{ employee.employeeNumber }}</span>
                                        </div>
                                        <div class="title">
                                            <span>Position:</span>
                                            <span class="small text-muted">{{ employee.designation.title }}</span>
                                        </div>
                                        <div class="title">
                                            <span>Date of Join :</span>
                                            <span
                                                class="small text-muted"
                                            >{{ $moment(employee.joinDate).format('MMM D YYYY') }}</span>
                                        </div>
                                        <div class="title">
                                            <span>Reports to:</span>
                                            <template v-if="!!employee.supervisor">
                                                <span class="small text-muted">
                                                    <a :href="`/hrms/employees/profile/${employee.supervisor.username}`"
                                                       target="__blank">
                                                        <span>{{ employee.supervisor.fullName }}</span>
                                                    </a>
                                                    <span class="small"
                                                          v-if="!!employee.supervisor.designation">({{
                                                            employee.supervisor.designation.title
                                                        }})</span>
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
                                                    {{ $moment(employee.nextWorkAnniversary).format("DD MMM, YYYY") }}
                                                </template>
                                            </span>
                                        </div>
                                        <div class="title">
                                            <span>Email: </span>
                                            <span class="text-muted">{{ employee.email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="roles-menu mt-0">
                        <ul>
                            <li class="active">
                                <a href="javascript:void(0);">Personal Info</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Contacts</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Bank</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Education</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Experience</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Related Persons</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Leaves</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Documents</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Download Profile</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Subordinates</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Delegations</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Appraisals</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8">
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
                                        <a href="#employee-contacts-info" data-toggle="tab"
                                           class="nav-link">Contacts</a>
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
                                        <a href="#employee-related-persons" @click="getRelatedPersonsInfo()"
                                           data-toggle="tab"
                                           class="nav-link">
                                            Related Persons
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#employee-leaves" @click="getLeavesInfo()" data-toggle="tab"
                                           class="nav-link">Leaves</a>
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
                                    <li class="nav-item">
                                        <a
                                            href="#employee-appraisals"
                                            data-toggle="tab"
                                            class="nav-link"
                                        >Appraisals</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <!-- Employee Info Tab -->
                        <div id="employee-info" class="pro-overview tab-pane fade active show">
                            <div class="row">
                                <div class="col-md-12">
                                    <PersonalInfo :employee.sync="employee"/>
                                </div>
                            </div>
                        </div>
                        <!-- /Employee Info Tab -->

                        <!-- Contacts Info Tab -->
                        <div id="employee-contacts-info" class="pro-overview tab-pane fade">
                            <div class="row">
                                <div class="col-md-6">
                                    <ContactsInfo
                                        :contactable-id="employee.id"
                                        :contacts.sync="employee.contacts"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <NextOfKinInfo :next-of-kin="employee.nextOfKin"/>
                                </div>
                            </div>
                        </div>
                        <!-- /Contacts Info Tab -->

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
                            <div class="row">
                                <div class="col-md-12">
                                    <EmployeeLeaveApplications :employee-id="employee.id"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <EmployeeLeaves :employee-id="employee.id"/>
                                </div>
                            </div>
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
                                <div v-for="subordinate in employee.subordinates"
                                     class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                                    <EmployeeProfileWidget :employee.sync="subordinate" :key="subordinate.id"/>
                                </div>
                            </div>
                        </div>
                        <!-- /Employee Subordinates  Tab -->

                        <!-- Delegations Tab -->
                        <div class="tab-pane fade" id="employee-delegations">

                        </div>
                        <!-- /Delegations  Tab -->

                        <!-- Appraisals Tab -->
                        <div class="tab-pane fade" id="employee-appraisals">
                            <h5>Employee Appraisals</h5>
                        </div>
                        <!-- /Appraisals  Tab -->
                    </div>
                </div>
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
import EmployeeLeaves from "../leaves/EmployeeLeaves";
import EmployeeLeaveApplications from "../leave-applications/EmployeeLeaveApplications";
import NextOfKinInfo from "./NextOfKinInfo";

export default {
    components: {
        NextOfKinInfo,
        EmployeeLeaves,
        EmployeeLeaveApplications,
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
        this.getEmployeeProfileData()
            .then(() => {
                //this.getLeaveApplications();
            })
            .catch(error => {
                console.log(error);
            });
        EventBus.$on([
            "PROFILE_INFO_SAVED",
            "PROFILE_PICTURE_UPLOADED",
            "CONTACT_INFO_SAVED",
        ], this.getEmployeeProfileData);
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
        }),
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
        updateProfile() {
            EventBus.$emit('UPDATE_EMPLOYEE_PROFILE', this.employee);
        },
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
            this.$emit('LOAD_EMPLOYEE_LEAVES');
            this.$emit('LOAD_EMPLOYEE_LEAVE_APPLICATIONS');
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
                data.append("id", this.employee.id);
                data.append("employeeNumber", this.employee.employeeNumber);
                data.append("username", this.employee.username);
                data.append("userId", this.employee.userId);
                data.append("avatar", this.profilePhoto);
                this.isUploading = true;
                let response = await this.$store.dispatch(
                    "UPLOAD_PROFILE_PICTURE",
                    data
                );
                EventBus.$emit("PROFILE_PICTURE_UPLOADED");
                this.isUploading = false;
                this.profilePhoto = null;
                await swal(response);
            } catch (error) {
                this.isUploading = false;
                this.profilePhoto = null;
                //this.$refs.profilePicture.click = null;
                this.isLoading = true;
                await swal({title: error, icon: "error"});
                //this.getEmployeeProfileData();
                this.isLoading = false;
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
