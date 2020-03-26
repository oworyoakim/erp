<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="/employees" class="btn btn-dark"><i class="fa fa-list"></i> Back</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
        <div v-else class="card card-body">
            <form @submit.prevent="saveEmployee()">
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
                                            {{title.title}}
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
                                    <input v-model="employee.first_name" class="form-control"
                                           v-bind:class="{'is-invalid': !!errors.first_name}" type="text">
                                    <span v-if="!!errors.first_name" class="invalid-feedback"
                                          v-text="errors.first_name"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Middle Name</label>
                                <div class="col-sm-8">
                                    <input v-model="employee.middle_name"
                                           v-bind:class="{'is-invalid': !!errors.middle_name}"
                                           class="form-control" type="text">
                                    <span v-if="!!errors.middle_name" class="invalid-feedback"
                                          v-text="errors.middle_name"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Last Name <span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input v-model="employee.last_name"
                                           v-bind:class="{'is-invalid': !!errors.last_name}"
                                           class="form-control" type="text">
                                    <span v-if="!!errors.last_name" class="invalid-feedback"
                                          v-text="errors.last_name"></span>
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
                                            {{gender.title}}
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
                                    <app-date-range-picker :config="dobConfig"
                                                         v-model="employee.dob"
                                                         :value="employee.dob"
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
                    </div>
                    <div class="submit-section">
                        <button @click.prevent="validateBasicInfo()"
                                class="btn btn-outline-info submit-btn pull-right"><i
                            class="fa fa-arrow-right "></i> Next
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
                                    <input v-model="employee.employee_number"
                                           @keyup="employee.employee_number = employee.employee_number.toUpperCase()"
                                           v-bind:class="{'is-invalid': !!errors.employee_number}" type="text"
                                           class="form-control text-capitalize" readonly>
                                    <span v-if="!!errors.employee_number"
                                          class="invalid-feedback"
                                          v-text="errors.employee_number"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Designation <span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select v-model="employee.designation_id"
                                            :disabled="!!employee.id"
                                            v-bind:class="{'is-invalid': !!errors.designation_id}"
                                            class="form-control"
                                            required>
                                        <option value="">Select designation...</option>
                                        <option v-for="designation in formSelectionOptions.designations"
                                                :value="designation.id" :key="designation.id">{{designation.title}}
                                        </option>
                                    </select>
                                    <span v-if="!!errors.designation_id"
                                          class="invalid-feedback"
                                          v-text="errors.designation_id"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Employment Term<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select v-model="employee.employment_term"
                                            v-bind:class="{'is-invalid': !!errors.employment_term}"
                                            class="form-control select"
                                            required>
                                        <option value="">Select employment term</option>
                                        <option v-for="term in formSelectionOptions.employmentTerms" :value="term.slug" :key="term.slug">
                                            {{term.title}}
                                        </option>
                                    </select>
                                    <span v-if="!!errors.employment_term" class="invalid-feedback"
                                          v-text="errors.employment_term"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Employment Type <span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select v-model="employee.employment_type"
                                            v-bind:class="{'is-invalid': !!errors.employment_type}"
                                            class="form-control select"
                                            required>
                                        <option value="">Select employment type</option>
                                        <option v-for="type in formSelectionOptions.employmentTypes" :value="type.slug" :key="type.slug">
                                            {{type.title}}
                                        </option>
                                    </select>
                                    <span v-if="!!errors.employment_type" class="invalid-feedback"
                                          v-text="errors.employment_type"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Joining Date <span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <app-date-range-picker :config="dojConfig"
                                                           v-model="employee.date_joined"
                                                           :value="employee.date_joined"
                                                           :has-errors="!!errors.date_joined">
                                    </app-date-range-picker>
                                    <span v-if="!!errors.date_joined" class="invalid-feedback"
                                          v-text="errors.date_joined"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button @click.prevent="prev()" class="btn btn-outline-info submit-btn pull-left"><i
                            class="fa fa-arrow-left "></i> Previous
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
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Role <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select v-model="employee.role_id" v-bind:class="{'is-invalid': !!errors.role_id}"
                                            class="form-control select">
                                        <option value="">Select....</option>
                                        <option v-for="role in formSelectionOptions.roles" :value="role.id"
                                                :key="role.id">
                                            {{role.name}}
                                        </option>
                                    </select>
                                    <span v-if="!!errors.role_id" class="invalid-feedback"
                                          v-text="errors.role_id"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Username <span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input v-model="employee.username" v-bind:class="{'is-invalid': !!errors.username}"
                                           class="form-control" type="text" autocomplete="off">
                                    <span v-if="!!errors.username" class="invalid-feedback"
                                          v-text="errors.username"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Password</label>
                                <div class="col-sm-8">
                                    <input v-model="employee.password" v-bind:class="{'is-invalid': !!errors.password}"
                                           class="form-control" type="password" autocomplete="off">
                                    <span v-if="!!errors.password" class="invalid-feedback"
                                          v-text="errors.password"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
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
                        <button :disabled="isSending" class="btn btn-primary submit-btn pull-right">Submit
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</template>

<script>
    import Errors from "../../../utils/Errors";
    import Employee from "../../../models/hrms/Employee";
    import {baseUrl} from "../../../app";
    import {mapGetters} from "vuex";
    import routes from "../../../routes";

    export default {
        props: ['title', 'nextId'],
        created() {
            this.getEmployeeAttributes();
            this.employee = JSON.parse(localStorage.getItem('employee')) || new Employee();
            if (!!this.employee.dob) {
                this.employee.dob = this.$moment(this.employee.dob).format('DD MMM YYYY');
            }
            if (!!this.employee.date_joined) {
                this.employee.date_joined = this.$moment(this.employee.date_joined).format('DD MMM YYYY');
            } else {
                this.employee.date_joined = this.$moment().format('DD MMM YYYY');
            }
        },
        mounted() {

        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'getFormSelections',
            }),
        },
        data() {
            return {
                employee: new Employee(),
                step: 1,
                password: '',
                errors: new Errors({
                    title: null,
                    first_name: null,
                    last_name: null,
                    middle_name: null,
                    gender: null,
                    dob: null,
                    nin: null,
                    nssf: null,
                    tin: null,
                    password: null,
                    username: null,
                    role_id: null,
                    directorate_id: null,
                    department_id: null,
                    designation_id: null,
                    employment_term: null,
                    employment_type: null,
                    employee_number: null,
                    date_joined: null,
                }),
                dobConfig: {
                    showDropdowns: true,
                    singleDatePicker: true,
                    minDate: this.$moment().subtract(75, 'years'), // 75 years ago
                    maxDate: this.$moment().subtract(18, 'years'), // 18 years ago
                    opens: "center",
                    locale: {
                        format: 'DD MMM YYYY'
                    }
                },
                dojConfig: {
                    showDropdowns: true,
                    singleDatePicker: true,
                    maxDate: this.$moment(), // today
                    opens: "center",
                    locale: {
                        format: 'DD MMM YYYY'
                    }
                },
                breadcrumbItems: [
                    {href: '/employees', text: 'Employees', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
                isSending: false,
            };
        },
        methods: {
            async getEmployeeAttributes() {
                try {
                    if (!!!this.employee.id) {
                        this.employee.designation_id = '';
                    }
                    await this.$store.dispatch('getFormSelections', this.employee);
                } catch (error) {
                    toastr.error(error);
                }
            },
            prev() {
                this.step--;
            },
            validateBasicInfo() {
                if (!!!this.employee.first_name) {
                    this.errors.set('first_name', 'First name is required');
                } else if (String(this.employee.first_name).trim().length < 3) {
                    this.errors.set('first_name', 'First name must have 3 or more characters long');
                } else if (this.errors.first_name) {
                    this.errors.clear('first_name');
                }
                if (!!!this.employee.last_name) {
                    this.errors.set('last_name', 'Last name is required');
                } else if (String(this.employee.last_name).trim().length < 3) {
                    this.errors.set('last_name', 'Last name must have 3 or more characters long');
                } else if (this.errors.last_name) {
                    this.errors.clear('last_name');
                }

                if (!!this.employee.middle_name && String(this.employee.middle_name).trim().length < 3) {
                    this.errors.set('middle_name', 'Middle name must have 3 or more characters long');
                } else if (this.errors.middle_name) {
                    this.errors.clear('middle_name');
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
                let start = this.$moment().subtract(75,'years');
                let end = this.$moment().subtract(18,'years');
                if (dob.length === 0 || !this.$moment(dob).isValid()) {
                    this.errors.set('dob', 'Please enter a valid date of birth');
                } else if (!this.$moment(dob).isBetween(start,end)) {
                    this.errors.set('dob', 'The employee must be between 18 years and 75 years old');
                } else if (this.errors.dob) {
                    this.errors.clear('dob');
                }

                localStorage.setItem('employee', JSON.stringify(this.employee));

                if (this.errors.has('first_name') || this.errors.has('last_name') || this.errors.has('middle_name') || this.errors.has('nin') || this.errors.has('nssf') || this.errors.has('tin') || this.errors.has('dob') || this.errors.has('gender')) {
                    return;
                }
                let initials = this.employee.last_name[0] + this.employee.first_name[0];
                if (!!this.employee.middle_name) {
                    initials += this.employee.middle_name[0];
                }
                initials = initials.toUpperCase();
                this.employee.employee_number = `PF/${initials}/${this.nextId}`;

                this.step++;
            },
            validateEmploymentInfo() {
                /*
                let partern = /^[A-Z0-9]+$/;
                let empNum = String(this.employee.employee_number).trim();
                if (empNum.length === 0) {
                    this.errors.set('employee_number', 'The employee number is required');
                }else if(!partern.test(empNum)){
                    this.errors.set('employee_number', 'The employee number must start with a  capital letter and can only contain capital letters and numbers!');
                }else if (this.errors.has('employee_number')) {
                    this.errors.clear('employee_number');
                }
                 */

                if (String(this.employee.designation_id).trim().length === 0) {
                    this.errors.set('designation_id', 'Please select a designation');
                } else {
                    let designation = this.formSelectionOptions.designations.find((desig) => {
                        return desig.id == this.employee.designation_id;
                    });
                    if (!!designation && designation.numHolders >= designation.maxHolders) {
                        this.errors.set('designation_id', "This position is filled up. Contact Head of HR!");
                    } else if (this.errors.has('designation_id')) {
                        this.errors.clear('designation_id');
                    }
                }

                if (String(this.employee.employment_term).trim().length === 0) {
                    this.errors.set('employment_term', 'Please select an employment term');
                } else if (this.errors.has('employment_term')) {
                    this.errors.clear('employment_term');
                }

                if (String(this.employee.employment_type).trim().length === 0) {
                    this.errors.set('employment_type', 'Please select an employment type');
                } else if (this.errors.has('employment_type')) {
                    this.errors.clear('employment_type');
                }

                let date_joined = String(this.employee.date_joined).trim();
                if (date_joined.length === 0 || !this.$moment(date_joined).isValid()) {
                    this.errors.set('date_joined', 'Please enter a valid joining date');
                } else if (this.$moment(this.employee.date_joined).isAfter(this.$moment())) {
                    this.errors.set('date_joined', 'The joining date cannot be higher than today');
                } else if (this.errors.date_joined) {
                    this.errors.clear('date_joined');
                }
                localStorage.setItem('employee', JSON.stringify(this.employee));
                if (this.errors.has('employee_number') || this.errors.has('designation_id') || this.errors.has('employment_term') || this.errors.has('employment_type') || this.errors.has('date_joined')) {
                    return;
                }
                this.step++;
            },
            isInvalidLoginInfo() {
                if (String(this.employee.role_id).trim().length === 0) {
                    this.errors.set('role_id', 'Please select a user group');
                } else if (this.errors.has('role_id')) {
                    this.errors.clear('role_id');
                }
                if (String(this.employee.username).trim().length === 0) {
                    this.errors.set('username', 'Username is required');
                } else if (this.formSelectionOptions.usernames.includes(this.employee.username)) {
                    this.errors.set('username', 'Username already taken');
                } else if (this.errors.has('username')) {
                    this.errors.clear('username');
                }

                if (String(this.employee.password).trim().length === 0) {
                    this.errors.set('password', 'Password is required');
                } else if (this.employee.password !== this.password) {
                    this.errors.set('password', 'The two passwords do not match');
                } else if (this.errors.has('password')) {
                    this.errors.clear('password');
                }
                localStorage.setItem('employee', JSON.stringify(this.employee));
                return this.errors.has('username') || this.errors.has('password') || this.errors.has('role_id');
            },

            async saveEmployee() {
                try {
                    this.isSending = true;
                    if (this.isInvalidLoginInfo()) {
                        this.isSending = false;
                        return;
                    }
                    let response = await this.$store.dispatch('SAVE_EMPLOYEE', this.employee);
                    localStorage.removeItem('employee');
                    this.isSending = false;
                    //toastr.success(response);
                    swal({title: response, icon: 'success'}).then(() => {
                        window.location = baseUrl + routes.EMPLOYEES;
                    });
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isSending = false;
                }
            },
        },
    }
</script>

<style scoped>

</style>
