<template>
    <div class="hrms-dashboard">
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <DashboardWidgets
                :total-employees="statisticsInfo.totalEmployees"
                :total-leaves-ongoing="statisticsInfo.totalLeavesOngoing"
                :total-leaves-upcoming="statisticsInfo.totalLeavesUpcoming"
                :total-leave-applications="statisticsInfo.totalLeaveApplications"
            />
            <div class="row">
                <div class="col-md-12">
                    <MorrisBarChart
                        :title="'Total Employees'"
                        :config="morrisBarChartConfig"
                        v-on:reload-data="getDashboardStatistics"
                    />
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import DashboardWidgets from "../../shared/DashboardWidgets";
    import MorrisBarChart from "../../charts/MorrisBarChart";

    export default {
        components: {MorrisBarChart, DashboardWidgets},
        props: ["title"],
        created(){
            this.getDashboardStatistics();
            console.log(this.$service);
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
