<template>
    <form v-if="!!employee" @submit.prevent="updateProfileInfo">
        <fieldset v-if="step === 1">
            <h2 class="mb-2 border-bottom">Basic Information</h2>
            <div class="row text-lg">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Title<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select v-model="employee.title" v-bind:class="{'is-invalid': !!errors.title}"
                                    class="form-control">
                                <option value="">Select...</option>
                                <option v-for="title in formSelectionOptions.titles" :value="title.slug"
                                        :key="title.slug">
                                    {{ title.title }}
                                </option>
                            </select>
                            <span v-if="!!errors.title" class="invalid-feedback" v-text="errors.title"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">First Name <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="employee.firstName" class="form-control"
                                   v-bind:class="{'is-invalid': !!errors.firstName}" type="text">
                            <span v-if="!!errors.firstName" class="invalid-feedback"
                                  v-text="errors.firstName"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Last Name <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="employee.lastName"
                                   v-bind:class="{'is-invalid': !!errors.lastName}"
                                   class="form-control" type="text">
                            <span v-if="!!errors.lastName" class="invalid-feedback"
                                  v-text="errors.lastName"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Other Names</label>
                        <div class="col-sm-8">
                            <input v-model="employee.otherNames"
                                   v-bind:class="{'is-invalid': !!errors.otherNames}"
                                   class="form-control" type="text">
                            <span v-if="!!errors.otherNames" class="invalid-feedback"
                                  v-text="errors.otherNames"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Gender <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select v-model="employee.gender" v-bind:class="{'is-invalid': !!errors.gender}"
                                    class="form-control custom-select">
                                <option value="">Select...</option>
                                <option v-for="gender in formSelectionOptions.genders" :key="gender.slug"
                                        :value="gender.slug">
                                    {{ gender.title }}
                                </option>
                            </select>
                            <span v-if="!!errors.gender" class="invalid-feedback"
                                  v-text="errors.gender"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Date of Birth <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <app-date-range-picker :config="$store.state.dobConfig"
                                                   v-model="employee.dob"
                                                   :value="employee.dob"
                                                   key="dob"
                                                   :has-errors="!!errors.dob">
                            </app-date-range-picker>
                            <span v-if="!!errors.dob" class="invalid-feedback"
                                  v-text="errors.dob"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">NIN <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="employee.nin" @keyup="employee.nin = employee.nin.toUpperCase()"
                                   v-bind:class="{'is-invalid': !!errors.nin}"
                                   type="text"
                                   class="form-control">
                            <span v-if="!!errors.nin" class="invalid-feedback"
                                  v-text="errors.nin"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Passport</label>
                        <div class="col-sm-8">
                            <input v-model="employee.passport"
                                   @keyup="employee.passport = employee.passport.toUpperCase()"
                                   v-bind:class="{'is-invalid': !!errors.passport}"
                                   type="text"
                                   class="form-control">
                            <span v-if="!!errors.passport" class="invalid-feedback"
                                  v-text="errors.passport"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">NSSF <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="employee.nssf" @keyup="employee.nssf = employee.nssf.toUpperCase()"
                                   v-bind:class="{'is-invalid': !!errors.nssf}"
                                   type="text"
                                   class="form-control">
                            <span v-if="!!errors.nssf" class="invalid-feedback"
                                  v-text="errors.nssf"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">TIN <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="employee.tin" @keyup="employee.tin = employee.tin.toUpperCase()"
                                   v-bind:class="{'is-invalid': !!errors.tin}"
                                   type="text"
                                   class="form-control">
                            <span v-if="!!errors.tin" class="invalid-feedback"
                                  v-text="errors.tin"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Religion</label>
                        <div class="col-sm-8">
                            <select v-model="employee.religion"
                                    v-bind:class="{'is-invalid': !!errors.religion}"
                                    class="form-control select"
                                    required>
                                <option value="">Select religion</option>
                                <option v-for="religion in formSelectionOptions.religions"
                                        :value="religion.title" :key="religion.title">
                                    {{ religion.title }}
                                </option>
                            </select>
                            <span v-if="!!errors.religion" class="invalid-feedback"
                                  v-text="errors.religion"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Marital Status</label>
                        <div class="col-sm-8">
                            <select v-model="employee.maritalStatus"
                                    v-bind:class="{'is-invalid': !!errors.maritalStatus}"
                                    class="form-control select"
                                    required>
                                <option value="">Select marital status</option>
                                <option v-for="maritalStatus in formSelectionOptions.maritalStatuses"
                                        :value="maritalStatus.title" :key="maritalStatus.title">
                                    {{ maritalStatus.title }}
                                </option>
                            </select>
                            <span v-if="!!errors.maritalStatus" class="invalid-feedback"
                                  v-text="errors.maritalStatus"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button @click.prevent="validateBasicInfo()"
                        class="btn btn-outline-info submit-btn pull-left"><i
                    class="fa fa-arrow-right "></i> Next
                </button>
                <button :disabled="isSending" class="btn btn-primary submit-btn pull-right">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
            </div>
        </fieldset>

        <fieldset v-if="step === 2">
            <h2 class="mb-2 border-bottom">Employment Information</h2>
            <div class="row text-lg">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Employee ID <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="employee.employeeNumber"
                                   @keyup="employee.employeeNumber = employee.employeeNumber.toUpperCase()"
                                   v-bind:class="{'is-invalid': !!errors.employeeNumber}" type="text"
                                   class="form-control text-capitalize">
                            <span v-if="!!errors.employeeNumber"
                                  class="invalid-feedback"
                                  v-text="errors.employeeNumber"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Designation <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select v-model="employee.designationId"
                                    :disabled="!!employee.id"
                                    v-bind:class="{'is-invalid': !!errors.designationId}"
                                    class="form-control"
                                    required>
                                <option value="">Select designation...</option>
                                <option v-for="designation in formSelectionOptions.designations"
                                        :value="designation.id" :key="designation.id">
                                    {{ designation.title }}
                                </option>
                            </select>
                            <span v-if="!!errors.designationId"
                                  class="invalid-feedback"
                                  v-text="errors.designationId"></span>
                            <a href="javascript:void(0)" class="" @click="editDesignation()">
                                <span class="fs-10 text-info">Add Designation</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Employment Term<span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select v-model="employee.employmentTerm"
                                    v-bind:class="{'is-invalid': !!errors.employmentTerm}"
                                    class="form-control select"
                                    required>
                                <option value="">Select employment term</option>
                                <option v-for="term in formSelectionOptions.employmentTerms"
                                        :value="term.slug" :key="term.slug">
                                    {{ term.title }}
                                </option>
                            </select>
                            <span v-if="!!errors.employmentTerm" class="invalid-feedback"
                                  v-text="errors.employmentTerm"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Employment Type <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select v-model="employee.employmentType"
                                    v-bind:class="{'is-invalid': !!errors.employmentType}"
                                    class="form-control select"
                                    required>
                                <option value="">Select employment type</option>
                                <option v-for="type in formSelectionOptions.employmentTypes"
                                        :value="type.slug" :key="type.slug">
                                    {{ type.title }}
                                </option>
                            </select>
                            <span v-if="!!errors.employmentType" class="invalid-feedback"
                                  v-text="errors.employmentType"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Joining Date <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <app-date-range-picker :config="$store.state.joinDateConfig"
                                                   v-model="employee.joinDate"
                                                   :value="employee.joinDate"
                                                   key="joinDate"
                                                   :has-errors="!!errors.joinDate">
                            </app-date-range-picker>
                            <span v-if="!!errors.joinDate" class="invalid-feedback"
                                  v-text="errors.joinDate"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Work Email <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="email"
                                   v-bind:class="{'is-invalid': !!errors.email}"
                                   type="email"
                                   class="form-control">
                            <span v-if="!!errors.email"
                                  class="invalid-feedback"
                                  v-text="errors.email"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button @click.prevent="prev()" class="btn btn-outline-info submit-btn pull-left"><i
                    class="fa fa-arrow-left "></i>
                    Previous
                </button>
                <button :disabled="isSending" class="btn btn-primary submit-btn mr-5">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
                <button @click.prevent="validateEmploymentInfo()"
                        class="btn btn-outline-info submit-btn pull-right"><i
                    class="fa fa-arrow-right "></i> Next
                </button>
            </div>
        </fieldset>

        <fieldset v-if="step === 3">
            <h2 class="mb-2 border-bottom">Login Information</h2>
            <div class="row text-lg">
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Username <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="employee.username"
                                   v-bind:class="{'is-invalid': !!errors.username}"
                                   class="form-control" type="text" autocomplete="off" disabled>
                            <span v-if="!!errors.username" class="invalid-feedback"
                                  v-text="errors.username"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Password</label>
                        <div class="col-sm-8">
                            <input v-model="employee.password"
                                   v-bind:class="{'is-invalid': !!errors.password}"
                                   class="form-control" type="password" autocomplete="off">
                            <span v-if="!!errors.password" class="invalid-feedback"
                                  v-text="errors.password"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Confirm Password</label>
                        <div class="col-sm-8">
                            <input v-model="password" v-bind:class="{'is-invalid': !!errors.password}"
                                   class="form-control" type="password" autocomplete="off">
                        </div>
                    </div>

                </div>
            </div>
            <div class="submit-section">
                <button @click.prevent="prev()" class="btn btn-outline-info submit-btn pull-left"><i
                    class="fa fa-arrow-left "></i>
                    Previous
                </button>
                <button :disabled="isSending" class="btn btn-primary submit-btn pull-right">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
            </div>
        </fieldset>
    </form>
</template>

<script>
import {mapGetters} from "vuex";
import {EventBus} from "../../../app";
import {deepClone} from "../../../utils/helpers";
import Errors from "../../../utils/Errors";

export default {
    computed: {
        ...mapGetters({
            formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
        }),
    },
    data() {
        return {
            isSending: false,
            employee: null,
            step: 1,
            password: '',
            email: '',
            errors: new Errors({
                title: null,
                firstName: null,
                lastName: null,
                middleName: null,
                otherNames: null,
                gender: null,
                dob: null,
                nin: null,
                nssf: null,
                tin: null,
                password: null,
                username: null,
                roleId: null,
                directorateId: null,
                departmentId: null,
                designationId: null,
                employmentTerm: null,
                employmentType: null,
                employeeNumber: null,
                joinDate: null,
                email: null,
                religion: null,
                maritalStatus: null,
            }),
        }
    },
    created() {
        EventBus.$on('UPDATE_EMPLOYEE_PROFILE', this.editProfileInfo);
    },
    methods: {
        async updateProfileInfo() {
            try {
                if (!this.validateBasicInfo(false) || !this.validateEmploymentInfo(false) || !this.validateLoginInfo()) {
                    return;
                }
                this.isSending = true;
                this.employee.email = this.email;
                let response = await this.$store.dispatch('SAVE_EMPLOYEE', this.employee);
                this.isSending = false;
                toastr.success(response);
                EventBus.$emit('PROFILE_INFO_SAVED');
                this.resetForm();
            } catch (error) {
                let message = document.createElement('div');
                //message.innerHTML = error.trim('"');
                message.innerHTML = error;
                await swal({content: message, icon: 'error'});
                this.isSending = false;
            }
        },
        editProfileInfo(employee) {
            if (!!employee) {
                this.employee = deepClone(employee);
                this.email = this.employee.email || '';
            } else {
                this.employee = null;
            }
        },
        prev() {
            this.step--;
        },
        validateBasicInfo(nextStep = true) {
            if (!!!this.employee.firstName) {
                this.errors.set('firstName', 'First name is required');
            } else if (String(this.employee.firstName).trim().length < 3) {
                this.errors.set('firstName', 'First name must have 3 or more characters long');
            } else if (this.errors.firstName) {
                this.errors.clear('firstName');
            }
            if (!!!this.employee.lastName) {
                this.errors.set('lastName', 'Last name is required');
            } else if (String(this.employee.lastName).trim().length < 3) {
                this.errors.set('lastName', 'Last name must have 3 or more characters long');
            } else if (this.errors.lastName) {
                this.errors.clear('lastName');
            }

            if (!!this.employee.middleName && String(this.employee.middleName).trim().length < 3) {
                this.errors.set('middleName', 'Middle name must have 3 or more characters long');
            } else if (this.errors.middleName) {
                this.errors.clear('middleName');
            }
            if (!!this.employee.otherNames && String(this.employee.otherNames).trim().length < 3) {
                this.errors.set('otherNames', 'Other names must have 3 or more characters long');
            } else if (this.errors.otherNames) {
                this.errors.clear('otherNames');
            }

            if (String(this.employee.gender).trim().length === 0) {
                this.errors.set('gender', 'Please select a gender');
            } else if (this.errors.gender) {
                this.errors.clear('gender');
            }

            if (String(this.employee.title).trim().length === 0) {
                this.errors.set('title', 'Please select a title');
            } else if (this.errors.title) {
                this.errors.clear('title');
            }

            let ninPattern = /^[A-Z0-9]+$/;
            if (String(this.employee.nin).trim().length === 0) {
                this.errors.set('nin', 'Please enter national ID number');
            } else if (!ninPattern.test(this.employee.nin)) {
                this.errors.set('nin', 'NIN can contain capital letters and numbers only!');
            } else if (this.employee.nin.length !== 14) {
                this.errors.set('nin', 'A valid NIN must contain 14 alphanumeric characters!');
            } else if (this.errors.nin) {
                this.errors.clear('nin');
            }

            let nssfPattern = /^[0-9]+$/;
            if (String(this.employee.nssf).trim().length === 0) {
                this.errors.set('nssf', 'Please enter NSSF');
            } else if (!nssfPattern.test(this.employee.nssf)) {
                this.errors.set('nssf', 'NSSF can contain numbers only!');
            } else if (this.employee.nssf.length !== 13) {
                this.errors.set('nssf', 'A valid NSSF must contain 13 numeric characters!');
            } else if (this.errors.nssf) {
                this.errors.clear('nssf');
            }

            let tinPattern = /^[0-9]+$/;
            if (String(this.employee.tin).trim().length === 0) {
                this.errors.set('tin', 'Please enter TIN');
            } else if (!tinPattern.test(this.employee.tin)) {
                this.errors.set('tin', 'TIN can contain numbers only!');
            } else if (this.employee.tin.length !== 10) {
                this.errors.set('tin', 'A valid TIN must contain 10 numeric characters!');
            } else if (this.errors.tin) {
                this.errors.clear('tin');
            }
            let dob = String(this.employee.dob).trim();
            let start = this.$moment().subtract(75, 'years');
            let end = this.$moment().subtract(18, 'years');
            if (dob.length === 0 || !this.$moment(dob).isValid()) {
                this.errors.set('dob', 'Please enter a valid date of birth');
            } else if (!this.$moment(dob).isBetween(start, end)) {
                this.errors.set('dob', 'The employee must be between 18 years and 75 years old');
            } else if (this.errors.dob) {
                this.errors.clear('dob');
            }

            localStorage.setItem('employee', JSON.stringify(this.employee));
            let formInvalid = this.errors.has('firstName') ||
                this.errors.has('lastName') ||
                this.errors.has('middleName') ||
                this.errors.has('nin') ||
                this.errors.has('nssf') ||
                this.errors.has('tin') ||
                this.errors.has('dob') ||
                this.errors.has('gender');
            if (formInvalid) {
                return false;
            }
            if (!this.employee.employeeNumber) {
                let initials = this.employee.lastName[0] + this.employee.firstName[0];
                initials = initials.toUpperCase();
                this.employee.employeeNumber = `PF/${initials}/${this.formSelectionOptions.nextEmployeeId}`;
            }
            if (nextStep) {
                this.step++;
            }
            return true;
        },
        validateEmploymentInfo(nextStep = true) {
            if (String(this.employee.designationId).trim().length === 0) {
                this.errors.set('designationId', 'Please select a designation');
            } else {
                let designation = this.formSelectionOptions.designations.find((designation) => {
                    return designation.id === this.employee.designationId;
                });
                if (!!designation && designation.id !== this.employee.designationId && designation.numHolders >= designation.maxHolders) {
                    this.errors.set('designationId', "This position is filled up. Contact Head of HR!");
                } else if (this.errors.has('designationId')) {
                    this.errors.clear('designationId');
                }
            }

            if (String(this.employee.employmentTerm).trim().length === 0) {
                this.errors.set('employmentTerm', 'Please select an employment term');
            } else if (this.errors.has('employmentTerm')) {
                this.errors.clear('employmentTerm');
            }

            if (String(this.employee.employmentType).trim().length === 0) {
                this.errors.set('employmentType', 'Please select an employment type');
            } else if (this.errors.has('employmentType')) {
                this.errors.clear('employmentType');
            }

            let joinDate = String(this.employee.joinDate).trim();
            if (joinDate.length === 0 || !this.$moment(joinDate).isValid()) {
                this.errors.set('joinDate', 'Please enter a valid joining date');
            } else if (this.$moment(this.employee.joinDate).isAfter(this.$moment())) {
                this.errors.set('joinDate', 'The joining date cannot be higher than today');
            } else if (this.errors.joinDate) {
                this.errors.clear('joinDate');
            }

            this.email = String(this.email).toLowerCase();
            if (String(this.email).trim().length === 0) {
                this.errors.set('email', 'Please enter an email address that will be used for logging in');
            }else if (this.email !== this.employee.email && this.formSelectionOptions.emails.includes(this.email)) {
                this.errors.set('username', 'Email already taken');
            } else if (!this.errors.validEmail(this.email)) {
                this.errors.set('email', 'Please enter a valid email address');
            } else if (this.errors.has('email')) {
                this.errors.clear('email');
            }

            let invalidData = this.errors.has('employeeNumber') ||
                this.errors.has('designationId') ||
                this.errors.has('employmentTerm') ||
                this.errors.has('employmentType') ||
                this.errors.has('joinDate') ||
                this.errors.has('email');
            if (invalidData) {
                return false;
            }
            if (nextStep) {
                this.step++;
            }
            return true;
        },
        validateLoginInfo() {
            if (String(this.employee.password).trim().length > 0 && this.employee.password !== this.password) {
                this.errors.set('password', 'The two passwords do not match');
            } else if (this.errors.has('password')) {
                this.errors.clear('password');
            }
            return this.errors.has('password');
        },
        resetForm() {
            this.employee = null;
        },
        editDesignation(designation = null) {
            EventBus.$emit("EDIT_DESIGNATION", designation);
        },
    },
}
</script>

<style scoped>

</style>
