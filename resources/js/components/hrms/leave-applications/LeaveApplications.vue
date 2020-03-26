<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <app-leave-applications-list :leave-applications="leaveApplications"></app-leave-applications-list>
            </div>
        </div>
        <app-leave-application-form></app-leave-application-form>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapActions, mapGetters} from "vuex";

    export default {
        created() {
            this.getLeaveApplications();
            this.getLeaveApplicationStatuses();
            this.getLeaveTypes();
            EventBus.$on(['leaveApplicationSaved','leaveApplicationDeleted'], this.getLeaveApplications);
        },
        data() {
            return {
                isLoading: true,
                filters: {
                    employee_id: '',
                    leave_type_id: '',
                },
            };
        },
        computed:{
            ...mapGetters({
                leaveApplications: 'GET_LEAVE_APPLICATIONS',
                leaveApplicationStatuses: 'getLeaveApplicationStatuses',
                leaveTypes: 'GET_LEAVE_TYPES',
            }),
        },
        methods: {
            ...mapActions(['GET_LEAVE_TYPES','getLeaveApplicationStatuses']),
            async getLeaveApplications() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_LEAVE_APPLICATIONS',this.filters);
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
