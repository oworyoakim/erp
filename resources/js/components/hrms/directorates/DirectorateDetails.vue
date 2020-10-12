<template>
    <div class="directorate-details">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb dashboard-link="/hrms" :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a :href="returnUri"
                       class="btn btn-dark btn-sm"><i class="fa fa-backward"></i> Back</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!directorate">
            <h4>Directorate with ID {{directorateId}} not found!</h4>
        </template>
        <template v-else>
            <div class="card mb-0">
                <div class="card-body text-lg">
                    <div class="row">
                        <div class="personal-info col-md-6">
                            <div class="title row">
                                <span class="col-sm-3 font-weight-bolder">Name:</span>
                                <span class="col-sm-9">{{directorate.title}}</span>
                            </div>
                            <div class="title row">
                                <span class="col-sm-3 font-weight-bolder">Description:</span>
                                <span class="col-sm-9">{{directorate.description}}</span>
                            </div>
                            <div class="title row">
                                <span class="col-sm-3 font-weight-bolder">Headed By :</span>
                                <span class="col-sm-9">
                                    <template v-if="!!directorate.head">
                                                <span class="small text-muted">
                                                    <a :href="`/hrms/employees/profile/${directorate.head.username}`"
                                                       target="__blank">
                                                        <span>{{directorate.head.fullName}}</span>
                                                    </a>
                                                    <span class="small" v-if="!!directorate.head.designation">({{directorate.head.designation.title}})</span>
                                                </span>
                                            </template>
                                </span>
                            </div>
                        </div>
                        <div class="personal-info col-md-6">
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <button @click="editDirectorate(directorate)" type="button"
                                            class="btn btn-outline-info btn-sm mr-5">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button @click="deleteDirectorate(directorate.id)" type="button"
                                            class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <DirectorateForm/>
                </div>
            </div>
            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item">
                                <a href="#directorate-departments" @click="getDepartments()" data-toggle="tab"
                                   class="nav-link active">Departments</a>
                            </li>
                            <li class="nav-item">
                                <a href="#directorate-divisions" @click="getDivisions()" data-toggle="tab"
                                   class="nav-link">Divisions</a>
                            </li>
                            <li class="nav-item">
                                <a href="#directorate-sections" @click="getSections()" data-toggle="tab"
                                   class="nav-link">Sections</a>
                            </li>
                            <li class="nav-item">
                                <a href="#directorate-employees" @click="getEmployees()" data-toggle="tab"
                                   class="nav-link">Employees</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <!-- Departments Tab -->
                <div id="directorate-departments" class="pro-overview tab-pane fade active show">
                    <div class="card mb-5">
                        <div class="card-body table-responsive">
                            <h3 class="card-title">Departments
                                <button type="button" class="btn add-btn btn-sm pull-right" @click="editDepartment()">
                                    <i class="fa fa-plus"></i> New Department
                                </button>
                            </h3>
                            <DepartmentsList
                                :departments.sync="departments"
                                :directorate-id="directorate.id"
                            />
                            <DepartmentForm :directorate-id="directorate.id"/>
                        </div>
                    </div>
                </div>
                <!-- Divisions Tab -->
                <div id="directorate-divisions" class="pro-overview tab-pane fade">
                    <div class="card mb-5">
                        <div class="card-body table-responsive">
                            <h3 class="card-title">Divisions
                                <button type="button" class="btn add-btn btn-sm pull-right" @click="editDivision()">
                                    <i class="fa fa-plus"></i> New Division
                                </button>
                            </h3>
                            <DivisionsList
                                :divisions.sync="divisions"
                                :directorate-id="directorate.id"
                            />
                            <DivisionForm :directorate-id="directorate.id"/>
                        </div>
                    </div>
                </div>
                <!-- Sections Tab -->
                <div id="directorate-sections" class="pro-overview tab-pane fade">
                    <div class="card mb-5">
                        <div class="card-body table-responsive">
                            <h3 class="card-title">Sections
                                <button type="button" class="btn add-btn btn-sm pull-right" @click="editSection()">
                                    <i class="fa fa-plus"></i> New Section
                                </button>
                            </h3>
                            <SectionsList :sections.sync="sections"
                                          :directorate-id="directorate.id"
                            />
                            <SectionForm :directorate-id="directorate.id"/>
                        </div>
                    </div>
                </div>
                <!-- Employees Tab -->
                <div id="directorate-employees" class="pro-overview tab-pane fade">
                    <div class="card mb-5">
                        <div class="card-body table-responsive">
                            <h3 class="card-title">Employees</h3>
                            <EmployeesList :employees.sync="employees"/>
                            <Pagination :items="employees" @gotoPage="getEmployees" />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {baseUrl, EventBus} from "../../../app";
    import routes from "../../../routes";
    import DirectorateForm from "../directorates/DirectorateForm";
    import DepartmentForm from "../departments/DepartmentForm";
    import DepartmentsList from "../departments/DepartmentsList";
    import DivisionsList from "../divisions/DivisionsList";
    import DivisionForm from "../divisions/DivisionForm";
    import EmployeesList from "../employees/EmployeesList";
    import SectionsList from "../sections/SectionsList";
    import SectionForm from "../sections/SectionForm";
    import Pagination from "../../shared/Pagination";

    export default {
        components: {
            Pagination,
            SectionForm,
            SectionsList,
            EmployeesList,
            DirectorateForm,
            DivisionForm,
            DivisionsList,
            DepartmentsList,
            DepartmentForm,
        },
        props: {
            directorateId: {type: Number, required: true},
            returnUri: String,
            title: String,
        },
        data() {
            return {
                isLoading: false,
                isDepartmentsLoading: false,
                breadcrumbItems: [
                    {href: '/hrms/directorate', text: 'Directorates', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
            }
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {directorateId: this.directorateId});
            this.getDirectorate().then(() => {
                this.getDepartments();
                // this.getDivisions();
                // this.getSections();
                // this.getEmployees();
            });
            EventBus.$on(['DIRECTORATE_SAVED', 'DIRECTORATE_DELETED'], this.getDirectorate);
            EventBus.$on(['DEPARTMENT_SAVED', 'DEPARTMENT_DELETED'], this.getDepartments);
            EventBus.$on(['DEPARTMENT_SAVED', 'DEPARTMENT_DELETED'], this.getDepartments);
            EventBus.$on(['DIVISION_SAVED', 'DIVISION_DELETED'], this.getDivisions);
            EventBus.$on(['SECTION_SAVED', 'SECTION_DELETED'], this.getSections);
            EventBus.$on([
                'DIRECTORATE_SAVED',
                'DIRECTORATE_DELETED',
                'DEPARTMENT_SAVED',
                'DEPARTMENT_DELETED',
                'DIVISION_SAVED',
                'DIVISION_DELETED',
                'SECTION_SAVED',
                'SECTION_DELETED',
            ], () => {
                this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {directorateId: this.directorateId});
            });

        },
        computed: {
            ...mapGetters({
                directorate: 'ACTIVE_DIRECTORATE',
                departments: 'DEPARTMENTS',
                divisions: 'DIVISIONS',
                sections: 'SECTIONS',
                employees: 'EMPLOYEES',
            }),
        },
        methods: {
            async getDirectorate() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_ACTIVE_DIRECTORATE', this.directorateId);
                    this.isLoading = false;
                } catch (error) {
                    this.isLoading = false;
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getDepartments() {
                try {
                    await this.$store.dispatch('GET_DEPARTMENTS', {directorateId: this.directorateId});
                    setTimeout(() => {
                        $('.departments-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getEmployees(page = 1) {
                try {
                    await this.$store.dispatch('GET_EMPLOYEES', {directorateId: this.directorateId,page: page});
                    setTimeout(() => {
                       // $('.employees-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getDivisions() {
                try {
                    await this.$store.dispatch('GET_DIVISIONS', {directorateId: this.directorateId});
                    setTimeout(() => {
                        $('.divisions-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getSections() {
                try {
                    await this.$store.dispatch('GET_SECTIONS', {directorateId: this.directorateId});
                    setTimeout(() => {
                        $('.sections-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);

                }
            },
            editDirectorate(directorate) {
                EventBus.$emit('EDIT_DIRECTORATE', directorate);
            },
            editDepartment(department = null) {
                EventBus.$emit('EDIT_DEPARTMENT', department);
            },
            editDivision(division = null) {
                EventBus.$emit('EDIT_DIVISION', division);
            },
            editSection(section = null) {
                EventBus.$emit('EDIT_SECTION', section);
            },
            async deleteDirectorate(directorate) {
                try {
                    let isConfirm = await swal({
                        title: 'Are you sure?',
                        text: "You will delete this record!",
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Yes'
                        ],
                        closeOnClickOutside: false
                    });
                    console.log(isConfirm);
                    if (isConfirm) {
                        let response = await this.$store.dispatch('DELETE_DIRECTORATE', directorate.id);
                        toastr.success(response);
                        location.href = baseUrl + routes.DIRECTORATES;
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        },
    }
</script>

<style scoped>

</style>
