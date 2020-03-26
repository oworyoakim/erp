<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" data-toggle="modal" data-target="#designationModal"><i
                        class="fa fa-plus"></i> New Designation
                    </button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
        <div v-else>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <app-designations-list :designations="designations"></app-designations-list>
                </div>
            </div>
            <app-designation-form
                :directorates="directorates"
                :departments="departments"
                :sections="sections"
                :designations="designations"
                :salary-scales="salaryScales">
            </app-designation-form>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        props: ['title'],
        created() {
            this.getDesignations();
            this.getSections();
            this.getDepartments();
            this.getDirectorates();
            this.getSalaryScales();
            EventBus.$on('getSection', this.getSections);
            EventBus.$on('GET_DEPARTMENTS', this.getDepartments);
            EventBus.$on('designationSaved', this.getDesignations);
            EventBus.$on('designationDeleted', this.getDesignations);
        },
        data() {
            return {
                filters: {
                    section_id: null,
                    salary_scale_id: null,
                    department_id: null,
                    directorate_id: null,
                },
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                designations: 'GET_DESIGNATIONS',
                directorates: 'GET_DIRECTORATES',
                departments: 'GET_DEPARTMENTS',
                sections: 'GET_SECTIONS',
                salaryScales: 'getSalaryScales',
            }),
        },
        methods: {
            async getDesignations() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DESIGNATIONS', this.filters);
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            async getDirectorates() {
                try {
                    await this.$store.dispatch('GET_DIRECTORATES');
                } catch (error) {
                    toastr.error(error);
                }
            },
            async getDepartments(directorate_id = null) {
                try {
                    await this.$store.dispatch('GET_DEPARTMENTS', {directorate_id: directorate_id});
                } catch (error) {
                    toastr.error(error);
                }
            },
            async getSections(department_id = null) {
                try {
                    await this.$store.dispatch('GET_SECTIONS', {department_id: department_id});
                } catch (error) {
                    toastr.error(error);
                }
            },
            async getSalaryScales() {
                try {
                    await this.$store.dispatch('getSalaryScales');
                } catch (error) {
                    toastr.error(error);
                }
            },
        }
    }
</script>
