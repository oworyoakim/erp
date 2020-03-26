<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <div class="row">
            <div class="col-md-12">
                <app-directorates-list :directorates="directorates"></app-directorates-list>
                <app-directorate-form></app-directorate-form>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        created() {
            this.getDirectorates();
            EventBus.$on('directorateSaved',this.getDirectorates);
            EventBus.$on('directorateDeleted',this.getDirectorates);
        },
        data() {
            return {
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                directorates: 'GET_DIRECTORATES',
            }),
        },
        methods: {
            async getDirectorates() {
                try{
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DIRECTORATES');
                    this.isLoading = false;
                    setTimeout(()=>{
                        $('.directorates-datatable').DataTable();
                    },100);
                }catch (error) {
                    toastr.error(error);
                }
            },
        },
    }
</script>

<style scoped>

</style>
