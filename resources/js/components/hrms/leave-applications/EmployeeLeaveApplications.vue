<template>
    <div class="employee-leave-applications">
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employeeId">
            <h4>No employee selected!</h4>
        </template>
        <template v-else>
            <div class="card profile-box flex-fill">
                <div class="card-body">
                    <h3 class="card-title">
                        Leave Applications
                        <button v-if="canApplyForLeave" type="button" class="btn add-btn btn-sm pull-right"
                                @click="editLeaveApplication()">
                            <i class="fa fa-plus"></i> Apply For Leave
                        </button>
                    </h3>
                    <LeaveApplicationsList
                        :leave-applications="leaveApplications"
                        :employee-id="employeeId"
                    />
                    <LeaveApplicationForm :employee-id="employeeId"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import LeaveApplicationsList from "./LeaveApplicationsList";
    import LeaveApplicationForm from "./LeaveApplicationForm";
    import {EventBus} from "../../../app";

    export default {
        props: {
            employeeId: {type: Number, required: true},
        },
        components: {LeaveApplicationForm, LeaveApplicationsList},
        computed: {
            ...mapGetters({
                leaveApplications: 'LEAVE_APPLICATIONS',
                user: 'getUser',
                employee: "ACTIVE_EMPLOYEE",
            }),
            canApplyForLeave() {
                return !!this.user && !!this.employee && this.user.id === this.employee.userId;
            },
        },
        created() {
            this.$parent.$on('LOAD_EMPLOYEE_LEAVE_APPLICATIONS', this.getLeaveApplications);
            EventBus.$on([
                'LEAVE_APPLICATION_SAVED',
                'LEAVE_APPLICATION_DELETED',
            ], this.getLeaveApplications);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        methods: {
            async getLeaveApplications() {
                try {
                    this.isLoading = true;
                    if (!!!this.employeeId) {
                        return await swal({
                            title: "Employee ID Missing",
                            icon: 'error'
                        });
                    }
                    await this.$store.dispatch("GET_LEAVE_APPLICATIONS", {employeeId: this.employeeId});
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    this.isLoading = false;
                }
            },
            editLeaveApplication(leaveApplication = null) {
                EventBus.$emit("EDIT_LEAVE_APPLICATION", leaveApplication);
            }
        },
    }
</script>

<style scoped>

</style>
