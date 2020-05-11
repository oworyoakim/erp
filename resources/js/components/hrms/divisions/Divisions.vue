<template>
    <div class="divisions">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb dashboard-link="/hrms" :list-items="breadcrumbItems"/>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" @click="editDivision()">
                        <i class="fa fa-plus"></i>
                        New Division
                    </button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <div class="row">
                <div class="col-md-12">
                    <DivisionsList :divisions="divisions"/>
                    <DivisionForm/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import DivisionsList from "./DivisionsList";
    import DivisionForm from "./DivisionForm";

    export default {
        props: ['title', 'returnUri', 'scope'],
        components: {
            DivisionsList,
            DivisionForm,
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {});
            this.getDivisions();
            EventBus.$on(['DIVISION_SAVED', 'DIVISION_DELETED'], this.getDivisions);
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
                divisions: 'DIVISIONS',
            }),
            directorates() {
                return this.formSelectionOptions.directorates;
            }
        },
        methods: {
            editDivision(division = null) {
                EventBus.$emit('EDIT_DIVISION', division);
            },
            async getDivisions() {
                try {
                    this.isLoading = true;
                    let response = await this.$store.dispatch('GET_DIVISIONS', {});
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
