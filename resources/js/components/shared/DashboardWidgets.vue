<template>
    <div class="row">
        <div v-for="stat in stats" class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <app-dashboard-widget :stat="stat"></app-dashboard-widget>
        </div>
    </div>
</template>

<script>
    import routes from "../../routes";

    export default {
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
                {title: 'Leaves Upcoming', count: this.totalLeavesUpcoming, icon: 'fa fa-cubes', link: '/leave-applications'},
                {title: 'Leaves Applications', count: this.totalLeaveApplications, icon: 'fa fa-diamond', link: '/leave-applications'}
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
