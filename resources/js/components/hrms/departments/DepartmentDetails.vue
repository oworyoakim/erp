<template>
    <div>
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
                            <span class="col-sm-9">{{department.directorate ? department.directorate.title : ''}}</span>
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
                                <button @click="deleteDepartment(department.id)" type="button"
                                        class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <app-department-form :scope="scope" :directorates="directorates"></app-department-form>
            </div>
        </div>
        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="#department-divisions" data-toggle="tab" class="nav-link active">Divisions</a>
                        </li>
                        <li class="nav-item"><a href="#department-sections" data-toggle="tab"
                                                class="nav-link">Sections</a>
                        </li>
                        <li class="nav-item"><a href="#department-employees" data-toggle="tab" class="nav-link">Employees</a>
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
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#divisionModal"><i class="fa fa-plus"></i> New Division
                            </button>
                        </h3>
                        <app-divisions-list :scope="scope" :divisions.sync="divisions"></app-divisions-list>
                        <app-division-form :scope="scope" :directorate_id="department.directorate_id"
                                           :department_id="department.id"></app-division-form>
                    </div>
                </div>
            </div>

            <!-- Sections Tab -->
            <div id="department-sections" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Sections
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#sectionModal"><i class="fa fa-plus"></i> New Section
                            </button>
                        </h3>
                        <app-sections-list :scope="scope" :sections.sync="sections"></app-sections-list>
                        <app-section-form :scope="scope" :directorate_id="department.directorate_id"
                                          :department_id="department.id"></app-section-form>
                    </div>
                </div>
            </div>
            <!-- Employees Tab -->
            <div id="department-employees" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Employees</h3>
                        <app-employees-list :department_id="department.id"
                                            :employees.sync="employees"></app-employees-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {baseUrl, EventBus} from "../../../app";
    import {mapActions, mapGetters} from "vuex";
    import routes from "../../../routes";

    export default {
        props: {
            department: Object,
            scope: String,
            returnUri: String,
            title: String,
        },
        created() {
            this.getDirectorates();
            this.getEmployees();
            this.getDivisions();
            this.getSections();
            EventBus.$on(['divisionSaved', 'divisionDeleted'], this.getDivisions);
            EventBus.$on(['sectionSaved', 'sectionDeleted','divisionSaved','divisionDeleted'], this.getSections);
        },
        data(){
            return {
                breadcrumbItems: [
                    {href: '/departments', text: 'Departments', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
            }
            },
        computed: {
            ...mapGetters({
                directorates: 'GET_DIRECTORATES',
                employees: 'GET_EMPLOYEES',
                divisions: 'GET_DIVISIONS',
                sections: 'GET_SECTIONS',
            }),
        },
        methods: {
            ...mapActions(['GET_DIRECTORATES']),
            async getEmployees() {
                try {
                    await this.$store.dispatch('GET_EMPLOYEES', {department_id: this.department.id,scope: this.scope});
                    $('.employees-datatable').DataTable();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getDivisions() {
                try {
                    await this.$store.dispatch('GET_DIVISIONS', {department_id: this.department.id,scope: this.scope});
                    $('.divisions-datatable').DataTable();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getSections() {
                try {
                    await this.$store.dispatch('GET_SECTIONS', {department_id: this.department.id,scope: this.scope});
                    $('.sections-datatable').DataTable();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);

                }
            },
            editDepartment(department) {
                EventBus.$emit('editDepartment', department);
            },
            async deleteDepartment(id) {
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
                        let response = await this.$store.dispatch('DELETE_DEPARTMENT', id);
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
