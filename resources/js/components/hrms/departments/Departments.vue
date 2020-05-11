<template>
    <div class="departments">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" @click="editDepartment()"><i class="fa fa-plus"></i> New Department</button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"></app-spinner>
        <template v-else>
            <div class="row">
                <div class="col-md-12">
                    <DepartmentsList :departments="departments"/>
                    <DepartmentForm />
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import DepartmentForm from "./DepartmentForm";
    import DepartmentsList from "./DepartmentsList";

    export default {
        props: ['directorateId','title','returnUri'],
        components:{
            DepartmentsList,
            DepartmentForm,
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS',{directorateId: this.directorateId});
            this.getDepartments();
            EventBus.$on(['DEPARTMENT_SAVED','DEPARTMENT_DELETED'], this.getDepartments);
        },
        data() {
            return {
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
                departments: 'DEPARTMENTS',
            }),
            directorates(){
                return this.formSelectionOptions.directorates;
            }
        },
        methods: {
            async getDepartments() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DEPARTMENTS', {directorateId: this.directorateId});
                    this.isLoading = false;
                    this.$nextTick(() => {
                        $('.departments-datatable').DataTable();
                    });
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editDepartment(department = null){
                EventBus.$emit("EDIT_DEPARTMENT",department);
            }
        },
    }
</script>

<style scoped>

</style>
