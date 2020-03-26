<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" data-target="#divisionModal" data-toggle="modal"><i class="fa fa-plus"></i>
                        New Division
                    </button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
        <div class="row" v-else>
            <div class="col-md-12">
                <app-divisions-list :divisions.sync="divisions"></app-divisions-list>
                <app-division-form></app-division-form>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: ['title', 'returnUri'],
        created() {
            this.getDivisions();
            EventBus.$on('divisionDeleted', this.getDivisions);
            EventBus.$on('divisionSaved', this.getDivisions);
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
                divisions: 'GET_DIVISIONS',
                formSelectionOptions: 'getFormSelections',
            }),
        },
        methods: {
            async getDivisions() {
                try {
                    this.isLoading = true;
                    let response = await this.$store.dispatch('GET_DIVISIONS', {});
                    console.log(response);
                    this.isLoading = false;
                    setTimeout(() => {
                        $('.divisions-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
        },
    }
</script>

<style scoped>

</style>
