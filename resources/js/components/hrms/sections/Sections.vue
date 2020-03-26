<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <div class="row">
            <div class="col-md-12">
                <app-sections-list :sections="sections"></app-sections-list>
                <app-section-form></app-section-form>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from '../../../app';

    export default {
        created() {
            this.getSections();
            EventBus.$on('sectionDeleted', this.getSections);
            EventBus.$on('sectionSaved', this.getSections);
        },
        data() {
            return {
                filters: {
                    department_id: null,
                },
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                sections: 'GET_SECTIONS',
            }),
        },
        methods: {
            async getSections() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_SECTIONS', this.filters);
                    this.isLoading = false;
                    setTimeout(()=>{$('.sections-datatable').DataTable();},100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
