<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" data-toggle="modal" data-target="#delegationModal"><i
                        class="fa fa-plus"></i> New Delegation
                    </button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
        <div class="row" v-else>
            <div class="col-md-12 table-responsive">
                <DelegationsList :delegations="delegations"/>
                <DelegationForm/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import DelegationsList from "./DelegationsList";
    import DelegationForm from "./DelegationForm";

    export default {
        components: {DelegationForm, DelegationsList},
        props: {
            title: String,
            employee_id: Number,
        },
        created() {
            this.getDelegations();
            EventBus.$on(['delegationSaved', 'delegationDeleted'], this.getDelegations);
        },
        data() {
            return {
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                delegations: 'GET_DELEGATIONS',
            }),
        },
        methods: {
            async getDelegations() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DELEGATIONS');
                    this.isLoading = false;
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
