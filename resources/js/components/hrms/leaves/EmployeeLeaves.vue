<template>
    <div class="employee-leave-applications">
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employeeId">
            <h4>No employee selected!</h4>
        </template>
        <template v-else>
            <div class="card profile-box flex-fill">
                <div class="card-body">
                    <h3 class="card-title">Leaves History</h3>
                    <LeavesList
                        :leaves="leaves"
                        :employee-id="employeeId"
                    />
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import LeavesList from "./LeavesList";

    export default {
        components: {LeavesList},
        props: {employeeId: {type: Number, required: true}},
        created() {
            this.$parent.$on('LOAD_EMPLOYEE_LEAVES', this.getLeaves);
            EventBus.$on([
                'LEAVE_SAVED',
                'LEAVE_DELETED',
            ], this.getLeaves);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                leaves: 'LEAVES'
            }),
        },
        methods: {
            async getLeaves() {
                try {
                    this.isLoading = true;
                    if (!!!this.employeeId) {
                        return await swal({
                            title: "Employee ID Missing",
                            icon: 'error'
                        });
                    }
                    //await this.$store.dispatch("GET_LEAVES", {employeeId: this.employeeId});
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    this.isLoading = false;
                }
            },
        }
    }
</script>

<style scoped>

</style>
