<template>
    <div class="designations">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" @click="editDesignation()"><i class="fa fa-plus"></i> New
                        Designation
                    </button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <DesignationsList :designations="designations"/>
                </div>
            </div>
            <DesignationForm/>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import DesignationForm from "./DesignationForm";
    import DesignationsList from "./DesignationsList";

    export default {
        props: ['title'],
        components: {DesignationsList, DesignationForm},
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS',{});
            this.getDesignations();
            EventBus.$on(['DESIGNATION_SAVED', 'DESIGNATION_DELETED'], this.getDesignations);
        },
        data() {
            return {
                filters: {
                    sectionId: null,
                    salaryScaleId: null,
                    departmentId: null,
                    directorateId: null,
                },
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                designations: 'DESIGNATIONS',
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
            editDesignation(designation = null) {
                EventBus.$emit("EDIT_DESIGNATION", designation);
            },
        }
    }
</script>
