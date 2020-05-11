<template>
    <div class="department-details">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a :href="returnUri"
                       class="btn btn-dark btn-sm"><i class="fa fa-backward"></i> Back</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"></app-spinner>
        <template v-else-if="!!!department">
            <h4>Department with ID {{departmentId}} not found!</h4>
        </template>
        <template v-else>
            <div class="card mb-0">
                <div class="card-body text-lg">
                    <div class="row">
                        <div class="personal-info col-md-6">
                            <div class="title row">
                                <span class="col-sm-3 font-weight-bolder">Name:</span>
                                <span class="col-sm-9">{{department.title}}</span>
                            </div>
                            <div class="title row">
                                <span class="col-sm-3 font-weight-bolder">Description:</span>
                                <span class="col-sm-9">{{department.description}}</span>
                            </div>
                            <div class="title row">
                                <span class="col-sm-3 font-weight-bolder">Headed by :</span>
                                <span class="col-sm-9"></span>
                            </div>
                            <div class="title row">
                                <span class="col-sm-3 font-weight-bolder">Directorate :</span>
                                <span class="col-sm-9">
                                    <template v-if="!!department.directorate">
                                        <a :href="'/hrms/directorates/view/' + department.directorateId">{{department.directorate.title}}</a>
                                    </template>
                                </span>
                            </div>
                        </div>
                        <div class="personal-info col-md-6">
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <button @click="editDepartment(department)" type="button"
                                            class="btn btn-outline-info btn-sm mr-5">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button @click="deleteDepartment(department)" type="button"
                                            class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <DepartmentForm :scope="scope"/>
                </div>
            </div>
            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item"><a href="#department-divisions" @click="getDivisions()"
                                                    data-toggle="tab" class="nav-link active">Divisions</a>
                            </li>
                            <li class="nav-item"><a href="#department-sections" @click="getSections()" data-toggle="tab"
                                                    class="nav-link">Sections</a>
                            </li>
                            <li class="nav-item"><a href="#department-employees" @click="getEmployees()"
                                                    data-toggle="tab" class="nav-link">Employees</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <!-- Divisions Tab -->
                <div id="department-divisions" class="pro-overview tab-pane fade active show">
                    <div class="card mb-5">
                        <div class="card-body table-responsive">
                            <h3 class="card-title">Divisions
                                <button type="button" class="btn add-btn btn-sm pull-right" @click="editDivision()">
                                    <i class="fa fa-plus"></i> New Division
                                </button>
                            </h3>
                            <DivisionsList
                                :directorate-id="department.directorateId"
                                :department-id="department.id"
                                :scope="scope"
                                :divisions="divisions"
                            />
                            <DivisionForm
                                :scope="scope"
                                :directorate-id="department.directorateId"
                                :department-id="department.id"
                            />
                        </div>
                    </div>
                </div>

                <!-- Sections Tab -->
                <div id="department-sections" class="pro-overview tab-pane fade">
                    <div class="card mb-5">
                        <div class="card-body table-responsive">
                            <h3 class="card-title">Sections
                                <button type="button" class="btn add-btn btn-sm pull-right" @click="editSection()">
                                    <i class="fa fa-plus"></i> New Section
                                </button>
                            </h3>
                            <SectionsList
                                :directorate-id="department.directorateId"
                                :department-id="department.id"
                                :scope="scope"
                                :sections="sections"
                            />
                            <SectionForm
                                :scope="scope"
                                :directorate-id="department.directorateId"
                                :department-id="department.id"
                            />
                        </div>
                    </div>
                </div>

                <!-- Employees Tab -->
                <div id="department-employees" class="pro-overview tab-pane fade">
                    <div class="card mb-5">
                        <div class="card-body table-responsive">
                            <h3 class="card-title">Employees</h3>
                            <EmployeesList
                                :directorate-id="department.directorateId"
                                :department-id="department.id"
                                :employees="employees"
                            />
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
    import DepartmentForm from "./DepartmentForm";
    import EmployeesList from "../employees/EmployeesList";
    import SectionsList from "../sections/SectionsList";
    import SectionForm from "../sections/SectionForm";
    import DivisionsList from "../divisions/DivisionsList";
    import DivisionForm from "../divisions/DivisionForm";

    export default {
        components: {
            DivisionForm,
            DivisionsList,
            SectionForm,
            SectionsList,
            EmployeesList,
            DepartmentForm,
        },
        props: {
            departmentId: {
                required: true
            },
            scope: String,
            returnUri: String,
            title: String,
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {scope: this.scope, departmentId: this.departmentId});
            this.getDepartment().then(() => {
                this.getDivisions();
            });
            EventBus.$on([
                'DEPARTMENT_SAVED',
            ], this.getDepartment);
            EventBus.$on([
                'SECTION_SAVED',
                'SECTION_DELETED',
            ], this.getSections)
            EventBus.$on([
                'DIVISION_SAVED',
                'DIVISION_DELETED',
            ], this.getDivisions);
            EventBus.$on([
                'DEPARTMENT_SAVED',
                'DIVISION_SAVED',
                'DIVISION_DELETED',
                'SECTION_SAVED',
                'SECTION_DELETED',
            ], () => {
                this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {
                    scope: this.scope,
                    departmentId: this.departmentId
                });
            });
        },
        data() {
            return {
                breadcrumbItems: [
                    {href: '/hrms/departments', text: 'Departments', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                department: 'ACTIVE_DEPARTMENT',
                divisions: 'DIVISIONS',
                sections: 'SECTIONS',
                employees: 'EMPLOYEES',
            }),
        },
        methods: {
            async getDepartment() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_ACTIVE_DEPARTMENT', {
                        departmentId: this.departmentId,
                        scope: this.scope
                    });
                    this.isLoading = false;
                    $('.employees-datatable').DataTable();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            async getEmployees() {
                try {
                    await this.$store.dispatch('GET_EMPLOYEES', {departmentId: this.department.id, scope: this.scope});
                    $('.employees-datatable').DataTable();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getDivisions() {
                try {
                    await this.$store.dispatch('GET_DIVISIONS', {departmentId: this.departmentId});
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
                    await this.$store.dispatch('GET_SECTIONS', {departmentId: this.departmentId});
                    setTimeout(() => {
                        $('.sections-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);

                }
            },
            editDepartment(department = null) {
                EventBus.$emit('EDIT_DEPARTMENT', department);
            },
            editSection(section = null) {
                EventBus.$emit('EDIT_SECTION', section);
            },
            editDivision(division = null) {
                EventBus.$emit('EDIT_DIVISION', division);
            },
            async deleteDepartment(department) {
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
                        let response = await this.$store.dispatch('DELETE_DEPARTMENT', department.id);
                        EventBus.$emit('DEPARTMENT_DELETED');
                        toastr.success(response);
                        location.href = baseUrl + routes.DEPARTMENTS;
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
