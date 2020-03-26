<template>
    <div>
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
                            <span class="col-sm-9"></span>
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
                <app-directorate-form></app-directorate-form>
            </div>
        </div>
        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a href="#directorate-departments" data-toggle="tab" class="nav-link active">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a href="#directorate-divisions" data-toggle="tab" class="nav-link">Divisions</a>
                        </li>
                        <li class="nav-item">
                            <a href="#directorate-sections" data-toggle="tab" class="nav-link">Sections</a>
                        </li>
                        <li class="nav-item">
                            <a href="#directorate-employees" data-toggle="tab" class="nav-link">Employees</a>
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
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#departmentModal"><i class="fa fa-plus"></i> New Department
                            </button>
                        </h3>
                        <app-departments-list :departments.sync="departments"></app-departments-list>
                        <app-department-form :directorate_id="directorate.id"></app-department-form>
                    </div>
                </div>
            </div>
            <!-- Divisions Tab -->
            <div id="directorate-divisions" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Divisions
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#divisionModal"><i class="fa fa-plus"></i> New Division
                            </button>
                        </h3>
                        <app-divisions-list :divisions.sync="divisions"></app-divisions-list>
                        <app-division-form :directorate_id="directorate.id"></app-division-form>
                    </div>
                </div>
            </div>
            <!-- Sections Tab -->
            <div id="directorate-sections" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-body table-responsive">
                        <h3 class="card-title">Sections
                            <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                    data-target="#sectionModal"><i class="fa fa-plus"></i> New Section
                            </button>
                        </h3>
                        <app-sections-list :sections.sync="sections"></app-sections-list>
                        <app-section-form :directorate_id="directorate.id"></app-section-form>
                    </div>
                </div>
            </div>
            <!-- Employees Tab -->
            <div id="directorate-employees" class="pro-overview tab-pane fade">
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
    import {baseUrl, EventBus} from "../../../app";
    import {mapActions, mapGetters} from "vuex";
    import routes from "../../../routes";

    export default {
        props: {
            directorate: Object,
        },
        data() {
            return {
                isDepartmentsLoading: false,
            }
        },
        created() {
            this.getDepartments();
            this.getDivisions();
            this.getSections();
            this.getEmployees();
            EventBus.$on(['departmentSaved', 'departmentDeleted'], this.getDepartments);
            EventBus.$on(['divisionSaved', 'divisionDeleted', 'departmentSaved', 'departmentDeleted'], this.getDivisions);
            EventBus.$on(['sectionSaved', 'sectionDeleted', 'divisionSaved', 'divisionDeleted', 'departmentSaved', 'departmentDeleted'], this.getSections);
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
                    await this.$store.dispatch('GET_DEPARTMENTS', {directorate_id: this.directorate.id});
                    setTimeout(() => {
                        $('.departments-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getEmployees() {
                try {
                    await this.$store.dispatch('GET_EMPLOYEES', {directorate_id: this.directorate.id});
                    setTimeout(() => {
                        $('.employees-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getDivisions() {
                try {
                    await this.$store.dispatch('GET_DIVISIONS', {directorate_id: this.directorate.id});
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
                    await this.$store.dispatch('GET_SECTIONS', {directorate_id: this.directorate.id});
                    setTimeout(() => {
                        $('.sections-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);

                }
            },
            editDirectorate(directorate) {
                EventBus.$emit('editDirectorate', directorate);
            },
            async deleteDirectorate(id) {
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
                        let response = await this.$store.dispatch('DELETE_DIRECTORATE', id);
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
