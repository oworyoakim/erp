<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">

                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a href="#executive-director-departments" @click="getDepartments()" data-toggle="tab" class="nav-link active">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a href="#executive-director-divisions" @click="getDivisions()" data-toggle="tab" class="nav-link">Divisions</a>
                        </li>
                        <li class="nav-item">
                            <a href="#executive-director-sections" @click="getSections()" data-toggle="tab" class="nav-link">Sections</a>
                        </li>
                        <li class="nav-item">
                            <a href="#executive-director-employees" @click="getEmployees()" data-toggle="tab" class="nav-link">Employees</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <!-- Departments Tab -->
            <div id="executive-director-departments" class="pro-overview tab-pane fade active show">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Departments
                            <button type="button" class="btn add-btn btn-sm pull-right" @click="editDepartment()"><i
                                class="fa fa-plus"></i> New Department
                            </button>
                        </h3>
                        <DepartmentsList :departments="departments" :scope="scope"/>
                        <DepartmentForm :scope="scope"/>
                    </div>
                </div>
            </div>
            <!-- Divisions Tab -->
            <div id="executive-director-divisions" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Divisions
                            <button type="button" class="btn add-btn btn-sm pull-right" @click="editDivision()">
                                <i class="fa fa-plus"></i> New Division
                            </button>
                        </h3>
                        <DivisionsList :divisions="divisions" :scope="scope"/>
                        <DivisionForm :scope="scope"/>
                    </div>
                </div>
            </div>
            <!-- Sections Tab -->
            <div id="executive-director-sections" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Sections
                            <button type="button" class="btn add-btn btn-sm pull-right" @click="editSection()">
                                <i class="fa fa-plus"></i> New Section
                            </button>
                        </h3>
                        <SectionsList :scope="scope" :sections="sections"/>
                        <SectionForm :scope="scope"/>
                    </div>
                </div>
            </div>
            <!-- Employees Tab -->
            <div id="executive-director-employees" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Employees</h3>
                        <EmployeesList :employees="employees"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import DepartmentsList from "../departments/DepartmentsList";
    import DepartmentForm from "../departments/DepartmentForm";
    import DivisionForm from "../divisions/DivisionForm";
    import DivisionsList from "../divisions/DivisionsList";
    import SectionsList from "../sections/SectionsList";
    import SectionForm from "../sections/SectionForm";
    import EmployeesList from "../employees/EmployeesList";

    export default {
        props: ['title'],
        components: {
            EmployeesList,
            SectionForm,
            SectionsList,
            DepartmentsList,
            DepartmentForm,
            DivisionsList,
            DivisionForm,
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {scope: this.scope});
            this.getDepartments();
            // this.getDivisions();
            // this.getSections();
            // this.getEmployees();
            EventBus.$on([
                'DEPARTMENT_SAVED',
                'DEPARTMENT_DELETED'
            ], this.getDepartments);
            EventBus.$on([
                'DIVISION_SAVED',
                'DIVISION_DELETED',
                'DEPARTMENT_SAVED',
                'DEPARTMENT_DELETED'
            ], this.getDivisions);
            EventBus.$on([
                'SECTION_SAVED',
                'SECTION_DELETED',
                'DIVISION_SAVED',
                'DIVISION_DELETED',
                'DEPARTMENT_SAVED',
                'DEPARTMENT_DELETED'
            ], this.getSections);
        },
        data() {
            return {
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                scope: 'executive-director',
            }
        },
        computed: {
            ...mapGetters({
                departments: 'DEPARTMENTS',
                divisions: 'DIVISIONS',
                sections: 'SECTIONS',
                employees: 'EMPLOYEES',
            }),
        },
        methods: {
            async getDepartments() {
                try {
                    await this.$store.dispatch('GET_DEPARTMENTS', {scope: this.scope});
                    setTimeout(() => {
                        $('.departments-datatable').DataTable();
                    }, 200);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getEmployees() {
                try {
                    await this.$store.dispatch('GET_EMPLOYEES', {scope: this.scope});
                    setTimeout(() => {
                        $('.employees-datatable').DataTable();
                    }, 200);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getDivisions() {
                try {
                    await this.$store.dispatch('GET_DIVISIONS', {scope: this.scope});
                    setTimeout(() => {
                        $('.divisions-datatable').DataTable();
                    }, 200);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getSections() {
                try {
                    await this.$store.dispatch('GET_SECTIONS', {scope: this.scope});
                    setTimeout(() => {
                        $('.sections-datatable').DataTable();
                    }, 200);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);

                }
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
        },
    }
</script>

<style scoped>

</style>
