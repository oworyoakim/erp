<template>
    <div class="leave-applications">
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <LeaveApplicationsList
                :leave-applications="leaveApplications"
            />
        </template>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import {EventBus} from "../../../app";
import LeaveApplicationsList from "./LeaveApplicationsList";

export default {
    components: {LeaveApplicationsList},
    props: {title: String},
    created() {
        this.getLeaveApplications();
        this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {});
        EventBus.$on([
            'LEAVE_APPLICATION_SAVED',
            'LEAVE_APPLICATION_DELETED',
        ], this.getLeaveApplications);
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
    computed: {
        ...mapGetters({
            leaveApplications: 'LEAVE_APPLICATIONS',
            formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
        }),
        leaveTypes() {
            return this.formSelectionOptions.leaveTypes;
        }
    },
    methods: {
        async getLeaveApplications() {
            try {
                this.isLoading = true;
                await this.$store.dispatch('GET_LEAVE_APPLICATIONS', this.filters);
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
