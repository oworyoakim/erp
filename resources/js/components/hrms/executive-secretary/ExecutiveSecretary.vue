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
                            <a href="#executive-secretary-departments" data-toggle="tab" class="nav-link active">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a href="#executive-secretary-divisions" data-toggle="tab" class="nav-link">Divisions</a>
                        </li>
                        <li class="nav-item">
                            <a href="#executive-secretary-sections" data-toggle="tab" class="nav-link">Sections</a>
                        </li>
                        <li class="nav-item">
                            <a href="#executive-secretary-employees" data-toggle="tab" class="nav-link">Employees</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <!-- Departments Tab -->
            <div id="executive-secretary-departments" class="pro-overview tab-pane fade active show">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Departments
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#departmentModal"><i class="fa fa-plus"></i> New Department
                            </button>
                        </h3>
                        <app-departments-list :scope="scope" :departments.sync="departments"></app-departments-list>
                        <app-department-form :scope="scope"></app-department-form>
                    </div>
                </div>
            </div>
            <!-- Divisions Tab -->
            <div id="executive-secretary-divisions" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Divisions
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#divisionModal"><i class="fa fa-plus"></i> New Division
                            </button>
                        </h3>
                        <app-divisions-list :scope="scope" :divisions.sync="divisions"></app-divisions-list>
                        <app-division-form :scope="scope"></app-division-form>
                    </div>
                </div>
            </div>
            <!-- Sections Tab -->
            <div id="executive-secretary-sections" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Sections
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#sectionModal"><i class="fa fa-plus"></i> New Section
                            </button>
                        </h3>
                        <app-sections-list :scope="scope" :sections.sync="sections"></app-sections-list>
                        <app-section-form :scope="scope"></app-section-form>
                    </div>
                </div>
            </div>
            <!-- Employees Tab -->
            <div id="executive-secretary-employees" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Employees</h3>
                        <app-employees-list :employees.sync="employees"></app-employees-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: ['title'],
        created() {
            this.getDepartments();
            this.getDivisions();
            this.getSections();
            this.getEmployees();
            EventBus.$on(['departmentSaved', 'departmentDeleted'], this.getDepartments);
            EventBus.$on(['divisionSaved', 'divisionDeleted', 'departmentSaved', 'departmentDeleted'], this.getDivisions);
            EventBus.$on(['sectionSaved', 'sectionDeleted', 'divisionSaved', 'divisionDeleted', 'departmentSaved', 'departmentDeleted'], this.getSections);
        },
        data() {
            return {
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                scope: 'executive-secretary',
            }
        },
        computed: {
            ...mapGetters({
                departments: 'GET_DEPARTMENTS',
                divisions: 'GET_DIVISIONS',
                sections: 'GET_SECTIONS',
                employees: 'GET_EMPLOYEES',
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
        },
    }
</script>

<style scoped>

</style>
