<template>
    <div class="sections">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb dashboard-link="/hrms" :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" @click="editSection()"><i class="fa fa-plus"></i> New Section</button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"></app-spinner>
    <div v-else>
        <div class="row">
            <div class="col-md-12">
                <SectionsList :sections="sections" :scope="scope"/>
                <SectionForm :scope="scope"/>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from '../../../app';
    import SectionsList from "./SectionsList";
    import SectionForm from "./SectionForm";

    export default {
        props: ["title",'scope'],
        components: {SectionForm, SectionsList},
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {scope: this.scope});
            this.getSections();
            EventBus.$on(['SECTION_SAVED', 'SECTION_DELETED'], this.getSections);
        },
        data() {
            return {
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                filters: {
                    departmentId: null,
                },
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                sections: 'SECTIONS',
            }),
        },
        methods: {
            async getSections() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_SECTIONS', this.filters);
                    this.isLoading = false;
                    setTimeout(() => {
                        $('.sections-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editSection(section = null){
                EventBus.$emit("EDIT_SECTION",section);
            }
        }
    }
</script>
