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
                            <span class="col-sm-4 font-weight-bolder">Name:</span>
                            <span class="col-sm-8">{{division.title}}</span>
                        </div>
                        <div class="title row">
                            <span class="col-sm-4 font-weight-bolder">Description:</span>
                            <span class="col-sm-8">{{division.description}}</span>
                        </div>
                        <div class="title row">
                            <span class="col-sm-4 font-weight-bolder">Headed by:</span>
                            <span class="col-sm-8"></span>
                        </div>
                        <div class="title row" v-if="division.directorate">
                            <span class="col-sm-4 font-weight-bolder">Directorate:</span>
                            <span class="col-sm-8">{{division.directorate.title}}</span>
                        </div>
                        <div class="title row" v-if="division.department">
                            <span class="col-sm-4 font-weight-bolder">Department:</span>
                            <span class="col-sm-8">{{division.department.title}}</span>
                        </div>
                    </div>
                    <div class="personal-info col-md-6">
                        <div class="row">
                            <div class="col-6"></div>
                            <div class="col-6">
                                <button @click="editDivision(division)" type="button"
                                        class="btn btn-outline-info btn-sm mr-5">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button @click="deleteDivision(division.id)" type="button"
                                        class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <app-division-form :scope="scope"></app-division-form>
            </div>
        </div>
        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="#department-sections" data-toggle="tab" class="nav-link active">Sections</a>
                        </li>
                        <li class="nav-item"><a href="#department-employees" data-toggle="tab" class="nav-link">Employees</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <!-- Sections Tab -->
            <div id="department-sections" class="pro-overview tab-pane fade active show">
                <div class="card mb-5">
                    <div class="card-header">
                        <h3 class="card-title">Sections</h3>
                        <button type="button" class="btn add-btn btn-sm pull-right" data-toggle="modal"
                                data-target="#sectionModal"><i class="fa fa-plus"></i> New Section
                        </button>
                    </div>
                    <div class="card-body table-responsive">
                        <span v-if="isSectionsLoading" class="fa fa-spinner fa-spin fa-5x"></span>
                        <app-sections-list :scope="scope" v-else :sections="sections"></app-sections-list>
                        <app-section-form :scope="scope" :directorate_id="division.directorate_id" :department_id="division.department_id" :division_id="division.id"></app-section-form>
                    </div>
                </div>
            </div>
            <!-- Employees Tab -->
            <div id="department-employees" class="pro-overview tab-pane fade">
                <div class="card mb-5">
                    <div class="card-header">
                        <h3 class="card-title">Employees</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <span v-if="isEmployeesLoading" class="fa fa-spinner fa-spin fa-5x"></span>
                        <app-employees-list v-else :division_id="division.id" :department_id="division.department_id"
                                            :employees="employees"></app-employees-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {baseUrl, EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import routes from "../../../routes";

    export default {
        props: {
            division: Object,
            scope: String,
            returnUri: String,
            title: String,
        },
        data() {
            return {
                isEmployeesLoading: false,
                isSectionsLoading: false,
                breadcrumbItems: [
                    {href: '/divisions', text: 'Divisions', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
            }
        },
        created() {
            this.getEmployees();
            this.getSections();
            EventBus.$on(['sectionSaved','sectionDeleted'], this.getSections);
        },
        computed: {
            ...mapGetters({
                departments: 'GET_DEPARTMENTS',
                directorates: 'GET_DIRECTORATES',
                employees: 'GET_EMPLOYEES',
                sections: 'GET_SECTIONS',
            }),
        },
        methods: {
            async getEmployees() {
                try {
                    this.isEmployeesLoading = true;
                    await this.$store.dispatch('GET_EMPLOYEES', {division_id: this.division.id,scope: this.scope});
                    this.isEmployeesLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isEmployeesLoading = false;
                }
            },
            async getSections() {
                try {
                    this.isSectionsLoading = true;
                    await this.$store.dispatch('GET_SECTIONS', {division_id: this.division.id,scope: this.scope});
                    this.isSectionsLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isSectionsLoading = false;
                }
            },
            editDivision(division) {
                EventBus.$emit('editDivision', division);
            },
            async deleteDivision(id) {
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
                        let response = await this.$store.dispatch('DELETE_DIVISION', id);
                        toastr.success(response);
                        location.href = baseUrl + routes.DIVISIONS;
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
