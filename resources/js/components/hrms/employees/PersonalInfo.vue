<template>
    <div class="card profile-box flex-fill">
        <div class="card-body" :class="isEditing && 'form-card'">
            <h2 v-if="isEditing">
                <a @click="editProfileInfo(false)"
                   class="edit-icon"
                   title="Close"
                   href="javascript:void(0)">
                    <i class="fa fa-times"></i>
                </a>
            </h2>
            <h3 class="mb-2 border-bottom" v-else>
                Particulars
                <a v-if="$store.getters.HAS_ANY_ACCESS(['employees.manage_profile'])" @click="editProfileInfo(true)"
                   class="edit-icon"
                   title="Edit"
                   href="javascript:void(0)">
                    <i class="fa fa-pencil"></i>
                </a>
            </h3>
            <template v-if="isEditing">
                <EmployeeProfileForm/>
                <DesignationForm />
            </template>
            <template v-else>
                <div class="card-text row">
                    <div class="col-6 basic-info">
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Title</div>
                            <div class="col-8 text">{{ employee.title }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">First Name</div>
                            <div class="col-8 text">{{ employee.firstName }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Last Name</div>
                            <div class="col-8 text">{{ employee.lastName }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Other Names</div>
                            <div class="col-8 text">{{ employee.otherNames }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">NIN.</div>
                            <div class="col-8 text">{{ employee.nin }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Passport No.</div>
                            <div class="col-8 text">{{ employee.passport }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Nationality</div>
                            <div class="col-8 text">{{ employee.nationality }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Religion</div>
                            <div v-if="employee.religion" class="col-8 text">{{ employee.religion }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Marital Status</div>
                            <div v-if="employee.maritalStatus" class="col-8 text">{{ employee.maritalStatus }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Gender</div>
                            <div class="col-8 text">{{ employee.gender }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">No. of children</div>
                            <div class="col-8 text"><span>Male: </span>{{ employee.numSons }}
                                <span>Female: </span>{{ employee.numDaughters }}
                                <span>Total: </span>{{ employee.numChildren }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">NSSF</div>
                            <div class="col-8 text">{{ employee.nssf }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">TIN</div>
                            <div class="col-8 text">{{ employee.tin }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4 attribute-title">Salary Scale</div>
                            <div class="col-8 text">
                                <template v-if="employee.salaryScale">
                                    {{ employee.salaryScale.scale }}
                                </template>
                            </div>
                        </div>
                        <div class="row mb-1" v-if="employee.directorate">
                            <div class="col-4 attribute-title">Directorate</div>
                            <div class="col-8 text">{{ employee.directorate.title }}</div>
                        </div>
                        <div class="row mb-1" v-if="employee.department">
                            <div class="col-4 attribute-title">Department</div>
                            <div class="col-8 text">{{ employee.department.title }}</div>
                        </div>
                        <div class="row mb-1" v-if="employee.division">
                            <div class="col-4 attribute-title">Division</div>
                            <div class="col-8 text">{{ employee.division.title }}</div>
                        </div>
                        <div class="row mb-1" v-if="employee.section">
                            <div class="col-4 attribute-title">Section</div>
                            <div class="col-8 text">{{ employee.section.title }}</div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import {EventBus} from "../../../app";
import EmployeeProfileForm from "./EmployeeProfileForm";
import DesignationForm from "../designations/DesignationForm";

export default {
    components: {DesignationForm, EmployeeProfileForm},
    props: {
        employee: Object,
    },
    created() {
        EventBus.$on([
            "PROFILE_INFO_SAVED",
        ], this.editProfileInfo);
    },
    data() {
        return {
            isEditing: false,
        }
    },
    methods: {
        editProfileInfo(shouldEdit = false) {
            this.isEditing = shouldEdit;
            this.$nextTick(() => {
                EventBus.$emit('UPDATE_EMPLOYEE_PROFILE', shouldEdit ? this.employee : null);
            });
        },
    }
}
</script>

<style scoped>
.attribute-title {
    font-weight: bolder !important;
}
</style>
