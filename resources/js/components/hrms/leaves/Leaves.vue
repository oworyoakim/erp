<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <LeaveStatistics :leaveTypes="leaveTypes"/>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <LeavesList :leaves="leaves"/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import LeaveStatistics from "./LeaveStatistics";
    import LeavesList from "./LeavesList";

    export default {
        components: {LeavesList, LeaveStatistics},
        computed: {
            ...mapGetters({
                leaves: 'LEAVES',
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            leaveTypes(){
                return this.formSelectionOptions.leaveTypes;
            }
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS',{});
            this.getLeaves();
        },
        data() {
            return {
                isLoading: true,
                filters: {
                    employeeId: '',
                    leaveTypeId: '',
                },
            };
        },
        methods: {
            async getLeaves() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_LEAVES',this.filters);
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
        }
    }
</script>
