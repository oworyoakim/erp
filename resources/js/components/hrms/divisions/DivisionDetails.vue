<template>
    <div class="division-details">
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
        <template v-else-if="!!!division">
            <h4>Division with ID {{departmentId}} not found!</h4>
        </template>
        <template v-else>
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
                                    <button @click="deleteDivision(division)" type="button"
                                            class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <DivisionForm
                        :directorate-id="division.directorateId"
                        :department-id="division.departmentId"
                        :scope="scope"
                    />
                </div>
            </div>
            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item"><a href="#department-sections" @click="getSections()" data-toggle="tab"
                                                    class="nav-link active">Sections</a>
                            </li>
                            <li class="nav-item"><a href="#department-employees" @click="getEmployees()"
                                                    data-toggle="tab" class="nav-link">Employees</a>
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
                            <button type="button" class="btn add-btn btn-sm pull-right" @click="editSection()">
                                <i class="fa fa-plus"></i> New Section
                            </button>
                        </div>
                        <div class="card-body table-responsive">
                            <SectionsList
                                :directorate-id="division.directorateId"
                                :department-id="division.departmentId"
                                :division-id="divisionId"
                                :scope="scope"
                                :sections="sections"
                            />
                            <SectionForm
                                :scope="scope"
                                :directorate-id="division.directorateId"
                                :department-id="division.departmentId"
                                :division-id="divisionId"
                            />
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
                            <EmployeesList
                                :division-id="division.id"
                                :department-id="division.departmentId"
                                :directorate-id="division.directorateId"
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
    import {baseUrl, EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import routes from "../../../routes";
    import DivisionForm from "./DivisionForm";
    import SectionsList from "../sections/SectionsList";
    import SectionForm from "../sections/SectionForm";
    import EmployeesList from "../employees/EmployeesList";

    export default {
        components: {
            DivisionForm,
            SectionsList,
            SectionForm,
            EmployeesList,
        },
        props: {
            divisionId: {type: Number, required: true},
            scope: String,
            returnUri: String,
            title: String,
        },
        data() {
            return {
                isLoading: false,
                breadcrumbItems: [
                    {href: '/hrms/divisions', text: 'Divisions', class: ''},
                    {href: '#', text: this.title, class: 'active'},
                ],
            }
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {divisionId: this.divisionId});
            this.getDivision().then(() => {
                this.getSections();
                //this.getEmployees();
            });
            this.getSections();
            EventBus.$on([
                'DIVISION_SAVED',
                'SECTION_SAVED',
                'SECTION_DELETED',
            ], this.getSections);
        },
        computed: {
            ...mapGetters({
                division: 'ACTIVE_DIVISION',
                sections: 'SECTIONS',
                employees: 'EMPLOYEES',
            }),
        },
        methods: {
            async getDivision() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_ACTIVE_DIVISION', {divisionId: this.divisionId, scope: this.scope});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            async getSections() {
                try {
                    await this.$store.dispatch('GET_SECTIONS', {divisionId: this.divisionId, scope: this.scope});
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getEmployees() {
                try {
                    await this.$store.dispatch('GET_EMPLOYEES', {divisionId: this.divisionId, scope: this.scope});
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editDivision(division = null) {
                EventBus.$emit('EDIT_DIVISION', division);
            },
            editSection(section = null) {
                EventBus.$emit('EDIT_SECTION', section);
            },
            async deleteDivision(division) {
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
                        let response = await this.$store.dispatch('DELETE_DIVISION', division.id);
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
