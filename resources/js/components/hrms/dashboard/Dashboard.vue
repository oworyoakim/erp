<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <app-dashboard-widgets :total-employees="statisticsInfo.totalEmployees"
                               :total-leaves-ongoing="statisticsInfo.totalLeavesOngoing"
                               :total-leaves-upcoming="statisticsInfo.totalLeavesUpcoming"
                               :total-leave-applications="statisticsInfo.totalLeaveApplications">
        </app-dashboard-widgets>
        <div class="row">
            <div class="col-md-12">
                <app-morris-bar-chart
                    :title="'Total Employees'"
                    :config="morrisBarChartConfig"
                    v-on:reload-data="getDashboardStatistics">
                </app-morris-bar-chart>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        props: ["title"],
        created(){
            this.getDashboardStatistics();
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed:{
          ...mapGetters({
              statisticsInfo: 'DASHBOARD_STATISTICS',
          }),
            morrisBarChartConfig() {
              return {
                  data: this.statisticsInfo.employeeStatistics,
                  xkey: 'directorate',
                  ykeys: ['male', 'female', 'other'],
                  labels: ['Male', 'Female', 'Other'],
                  lineColors: ['#f43b48', '#453a94', '#407a52'],
                  lineWidth: '1px',
                  barColors: ['#f43b48', '#453a94', '#407a52'],
                  resize: true,
                  redraw: true
              };
            },
        },
        methods: {
            async getDashboardStatistics(){
                try{
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DASHBOARD_STATISTICS');
                    this.isLoading = false;
                }catch (error) {
                    console.error(error);
                    await swal({title: error,icon: 'error'});
                }
            },
        }
    }
</script>

<style scoped>

</style>
