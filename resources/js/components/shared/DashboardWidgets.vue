<template>
    <div class="row">
        <div v-for="(stat,index) in stats" class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <DashboardWidget :stat="stat" :key="`stat-${index}`"/>
        </div>
    </div>
</template>

<script>
    import routes from "../../routes";
    import DashboardWidget from "./DashboardWidget";

    export default {
        components: {DashboardWidget},
        props: [
            "totalEmployees",
            "totalLeavesOngoing",
            "totalLeavesUpcoming",
            "totalLeaveApplications"
        ],
        created() {
            this.stats = [
                {title: 'Employees', count: this.totalEmployees, icon: 'fa fa-users', link: routes.EMPLOYEES},
                {title: 'Leaves Ongoing', count: this.totalLeavesOngoing, icon: 'fa fa-refresh', link: '#'},
                {title: 'Leaves Upcoming', count: this.totalLeavesUpcoming, icon: 'fa fa-cubes', link: '/hrms/leave-applications'},
                {title: 'Leaves Applications', count: this.totalLeaveApplications, icon: 'fa fa-diamond', link: '/hrms/leave-applications'}
            ];
        },
        data() {
            return {
                stats: [],
            }
        },
    }
</script>

<style scoped>

</style>
