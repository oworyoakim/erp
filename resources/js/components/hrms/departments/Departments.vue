<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" data-toggle="modal" data-target="#departmentModal"><i class="fa fa-plus"></i> New Department</button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
        <div class="row" v-else>
            <div class="col-md-12">
                <app-departments-list :departments="departments"></app-departments-list>
                <app-department-form></app-department-form>
            </div>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapActions, mapGetters} from "vuex";

    export default {
        props: ['title',,'returnUri'],
        created() {
            this.$store.dispatch('GET_DIRECTORATES');
            this.getDepartments();
            EventBus.$on('departmentDeleted', this.getDepartments);
            EventBus.$on('departmentSaved', this.getDepartments);
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
                directorates: 'GET_DIRECTORATES',
                departments: 'GET_DEPARTMENTS',
            }),
        },
        methods: {
            async getDepartments(directorate_id = null) {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DEPARTMENTS', {directorate_id: directorate_id});
                    this.isLoading = false;
                    this.$nextTick(() => {
                        $('.departments-datatable').DataTable();
                    });
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
