<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="/employees/create" class="btn add-btn"><i class="fa fa-plus"></i> New Employee</a>
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
                    <select v-model="filters.status" class="form-control select floating">
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
                    <select v-model="filters.employment_term" class="form-control select floating">
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
                    <select v-model="filters.employment_type" class="form-control select floating">
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
                    <select v-model="filters.employment_status" class="form-control select floating">
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
                        :select-options.sync="directoratesOptions"
                        v-model="filters.directorate_id"
                        :value="filters.directorate_id"
                        v-on:input="getFormSelectionOptions('directorate')"
                    ></app-select-box>
                    <label class="focus-label">Directorate</label>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group form-focus select-focus">
                    <app-select-box
                        v-model="filters.department_id"
                        :select-options.sync="departmentsOptions"
                        v-on:input="getFormSelectionOptions('department')"
                        :value="filters.department_id"
                    ></app-select-box>
                    <label class="focus-label">Department</label>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group form-focus select-focus">
                    <app-select-box
                        :select-options.sync="designationsOptions"
                        v-model="filters.designation_id"
                        :value="filters.designation_id"
                    ></app-select-box>
                    <label class="focus-label">Designation</label>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <button :disabled="isLoading" type="button" @click="getEmployees" class="btn btn-success btn-block">
                    Search
                </button>
            </div>
        </div>
        <!-- Search Filter -->
        <div class="row staff-grid-row">
            <div v-if="isLoading" class="col-md-12 text-center">
                <span class="fa fa-spinner fa-spin fa-5x"></span>
            </div>
            <div v-else-if="widgetView" v-for="employee in employees"
                 class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                <app-employee-profile-widget :employee.sync="employee"></app-employee-profile-widget>
            </div>
            <div v-else class="col-md-12 table-responsive">
                <app-employees-list :employees.sync="employees"></app-employees-list>
            </div>
        </div>
        <!-- Employee Modal -->
        <div id="employeeModal" ref="employeeModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-xl font-weight-bolder">Employee Registration Form</h1>
                        <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- /Employee Modal -->
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        props: ['title'],
        created() {
            this.getEmployees().then(() => {
                this.getFormSelectionOptions();
            });
        },
        data() {
            return {
                filters: {
                    name: '',
                    employee_status: '',
                    employment_term: '',
                    employment_type: '',
                    employment_status: '',
                    directorate_id: '',
                    department_id: '',
                    division_id: '',
                    section_id: '',
                    designation_id: '',
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
                employees: 'GET_EMPLOYEES',
                formSelectionOptions: 'getFormSelections',
            }),
            directoratesOptions() {
                return this.formSelectionOptions.directorates.map((directorate) => {
                    return {
                        text: directorate.title,
                        value: directorate.id
                    }
                });
            },
            departmentsOptions() {
                return this.formSelectionOptions.departments.map((department) => {
                    return {
                        text: department.title,
                        value: department.id
                    }
                });
            },
            designationsOptions() {
                return this.formSelectionOptions.designations.map((designation) => {
                    return {
                        text: designation.title,
                        value: designation.id
                    }
                });
            },
        },
        methods: {
            async getEmployees() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_EMPLOYEES', this.filters);
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            getFormSelectionOptions(changed = '') {
                try {
                    if (changed === 'directorate') {
                        this.filters.department_id = '';
                        this.filters.designation_id = '';
                    } else if (changed === 'department') {
                        this.filters.designation_id = '';
                    }
                    this.$store.dispatch('getFormSelections', this.filters);
                } catch (error) {
                    toastr.error(error);
                }
            },
        },
    }
</script>

<style scoped>

</style>
