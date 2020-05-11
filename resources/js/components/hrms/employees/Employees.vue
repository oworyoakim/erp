<template>
    <div class="employees">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="/hrms/employees/create" class="btn add-btn"><i class="fa fa-plus"></i> New Employee</a>
                    <div class="view-icons">
                        <button :disabled="widgetView" @click="widgetView = true" class="grid-view btn btn-link"
                                v-bind:class="{active: widgetView}"><i class="fa fa-th"></i></button>
                        <button :disabled="!widgetView" @click="widgetView = false" class="list-view btn btn-link"
                                v-bind:class="{active: !widgetView}"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group form-focus">
                        <input v-model="filters.name" type="text" class="form-control floating">
                        <label class="focus-label">Name</label>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group form-focus select-focus">
                        <select v-model="filters.employeeStatus" class="form-control select floating">
                            <option value="">Select...</option>
                            <option v-for="employeeStatus in formSelectionOptions.employeeStatuses"
                                    :value="employeeStatus.slug">
                                {{employeeStatus.title}}
                            </option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group form-focus select-focus">
                        <select v-model="filters.employmentTerm" class="form-control select floating">
                            <option value="">Select...</option>
                            <option v-for="employmentTerm in formSelectionOptions.employmentTerms"
                                    :value="employmentTerm.slug">
                                {{employmentTerm.title}}
                            </option>
                        </select>
                        <label class="focus-label">Term</label>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group form-focus select-focus">
                        <select v-model="filters.employmentType" class="form-control select floating">
                            <option value="">Select...</option>
                            <option v-for="employmentType in formSelectionOptions.employmentTypes"
                                    :value="employmentType.slug">
                                {{employmentType.title}}
                            </option>
                        </select>
                        <label class="focus-label">Type</label>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group form-focus select-focus">
                        <select v-model="filters.employmentStatus" class="form-control select floating">
                            <option value="">Select...</option>
                            <option v-for="employmentStatus in formSelectionOptions.employmentStatuses"
                                    :value="employmentStatus.slug">
                                {{employmentStatus.title}}
                            </option>
                        </select>
                        <label class="focus-label">Employment</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="form-group form-focus select-focus">
                        <app-select-box
                            :select-options="directoratesOptions"
                            v-model="directorateId"
                            :value="directorateId"
                            searchable
                        />
                        <label class="focus-label">Directorate</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="form-group form-focus select-focus">
                        <app-select-box
                            :select-options="departmentsOptions"
                            v-model="departmentId"
                            :value="departmentId"
                            searchable
                        />
                        <label class="focus-label">Department</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="form-group form-focus select-focus">
                        <app-select-box
                            :select-options="designationsOptions"
                            v-model="designationId"
                            :value="designationId"
                            searchable
                        />
                        <label class="focus-label">Designation</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button :disabled="isLoading"
                            type="button"
                            @click="getEmployees"
                            class="btn btn-success btn-block">
                        Search
                    </button>
                </div>
            </div>
            <!-- Search Filter -->
            <div class="row staff-grid-row">
                <template v-if="widgetView">
                    <div v-for="employee in employees" class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                        <EmployeeProfileWidget :employee.sync="employee" :key="Math.random()"/>
                    </div>
                </template>
                <template v-else>
                    <div class="col-md-12 table-responsive">
                        <EmployeesList :employees.sync="employees" :key="Math.random()"/>
                    </div>
                </template>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import EmployeesList from "./EmployeesList";
    import EmployeeProfileWidget from "./EmployeeProfileWidget";

    export default {
        components: {EmployeeProfileWidget, EmployeesList},
        props: ['title'],
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', this.filters);
            this.getEmployees();
        },
        data() {
            return {
                directorateId: '',
                departmentId: '',
                designationId: '',
                filters: {
                    name: '',
                    employeeStatus: '',
                    employmentTerm: '',
                    employmentType: '',
                    employmentStatus: '',
                    directorateId: '',
                    departmentId: '',
                    divisionId: '',
                    sectionId: '',
                    designationId: '',
                },
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                widgetView: true,
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                employees: 'EMPLOYEES',
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            directorates() {
                return this.formSelectionOptions.directorates;
            },
            departments() {
                if (!!this.filters.directorateId) {
                    return this.formSelectionOptions.departments.filter((department) => department.directorateId == this.filters.directorateId);
                }
                return this.formSelectionOptions.departments;
            },

            designations() {
                if (!!this.filters.directorateId) {
                    return this.formSelectionOptions.designations.filter((designation) => designation.directorateId == this.directorateId);
                }
                if (!!this.filters.departmentId) {
                    return this.formSelectionOptions.designations.filter((designation) => designation.departmentId == this.departmentId);
                }
                if (!!this.filters.divisionId) {
                    return this.formSelectionOptions.designations.filter((designation) => designation.divisionId == this.filters.divisionId);
                }
                if (!!this.filters.sectionId) {
                    return this.formSelectionOptions.designations.filter((designation) => designation.sectionId == this.filters.sectionId);
                }
                return this.formSelectionOptions.designations;
            },

            directoratesOptions() {
                return this.directorates.map((directorate) => {
                    return {
                        text: directorate.title,
                        value: directorate.id,
                    }
                });
            },
            departmentsOptions() {
                return this.departments.map((department) => {
                    return {
                        text: department.title,
                        value: department.id,
                    }
                });
            },
            designationsOptions() {
                return this.designations.map((designation) => {
                    return {
                        text: designation.title,
                        value: designation.id,
                    }
                });
            }
        },
        watch: {
            directorateId(newVal, oldVal) {
                this.filters.departmentId = '';
                this.filters.designationId = '';
            },
            departmentId(newVal, oldVal) {
                this.filters.designationId = '';
            }
        },
        methods: {
            async getEmployees() {
                try {
                    this.isLoading = true;
                    this.filters.directorateId = this.directorateId;
                    this.filters.departmentId = this.departmentId;
                    this.filters.designationId = this.designationId;
                    await this.$store.dispatch('GET_EMPLOYEES', this.filters);
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
        },
    }
</script>

<style scoped>

</style>
