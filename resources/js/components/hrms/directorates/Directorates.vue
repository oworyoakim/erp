<template>
    <div class="directorates">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb dashboard-link="/hrms" :list-items="breadcrumbItems"/>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button v-if="$store.getters.HAS_ANY_ACCESS(['directorates.create'])"  type="button" class="btn add-btn" @click="editDirectorate()">
                        <i class="fa fa-plus"></i>
                        New Directorate
                    </button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <div class="row">
                <div class="col-md-12">
                    <DirectoratesList :directorates="directorates"/>
                    <DirectorateForm/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import DirectoratesList from "./DirectoratesList";
    import DirectorateForm from "./DirectorateForm";

    export default {
        props: {title: String},
        components: {DirectoratesList, DirectorateForm},
        created() {
            this.getDirectorates();
            EventBus.$on(['DIRECTORATE_SAVED', 'DIRECTORATE_DELETED'], this.getDirectorates);
        },
        data() {
            return {
                isLoading: false,
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
            };
        },
        computed: {
            ...mapGetters({
                directorates: 'DIRECTORATES',
            }),
        },
        methods: {
            async getDirectorates() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DIRECTORATES');
                    this.isLoading = false;
                    setTimeout(() => {
                        $('.directorates-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    toastr.error(error);
                }
            },
            editDirectorate(directorate = null) {
                EventBus.$emit('EDIT_DIRECTORATE', directorate);
            },
        },
    }
</script>

<style scoped>

</style>
