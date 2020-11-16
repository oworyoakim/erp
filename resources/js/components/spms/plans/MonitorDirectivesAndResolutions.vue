<template>
    <div class="directives-and-resolutions-monitor">
        <div class="row">
            <div class="col-sm-4">
                <label>Select a strategic plan</label>
                <select v-model="filters.planId" class="form-control" @change="setActivePlan($event.target.value)">
                    <option value="">Select...</option>
                    <option v-for="plan in plans"
                            :value="plan.id"
                            :selected="activePlan && activePlan.id === plan.id"
                            :key="plan.id">
                        {{plan.name}}
                    </option>
                </select>
            </div>
            <div class="col-sm-4">
                <template v-if="!!filters.planId">
                    <label>Select a work plan</label>
                    <select v-model="filters.workPlanId" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="workPlan in workPlans"
                                :value="workPlan.id"
                                :key="workPlan.id">
                            {{workPlan.title}}
                        </option>
                    </select>
                </template>
            </div>
            <div class="col-sm-4" v-if="!!filters.planId && !!filters.workPlanId">
                <button class="btn btn-sm btn-secondary mt-4" type="button" @click="printReport()"><i
                    class="fa fa-print"></i> Print
                </button>
                <button class="btn btn-sm btn-primary mt-4" type="button" @click="downloadPdfReport()"><i
                    class="fa fa-file-pdf-o"></i> PDF
                </button>
            </div>
        </div>

        <div class="mt-3">
            <template v-if="isLoading">
                <app-spinner></app-spinner>
            </template>
            <template v-else-if="!!reportData">
                <div class="row" id="directives-and-resolutions-monitor-report">
                    <div class="col-md-12 table-responsive">
                        <table class="table-bordered table-sm" width="100%">
                            <tr>
                                <td>
                                    <img :src="'data:image/png;base64,' + reportData.companyLogo" class="img-fluid small" alt="UNEB-SPMS"/>
                                </td>
                                <td colspan="7" class="text-center">
                                    <h3>UGANDA NATIONAL EXAMINATIONS BOARD</h3>
                                    <h4 class="text-bold text-uppercase">
                                        PERFORMANCE REPORT FOR THE UNEB {{reportData.workPlan.plan.name}}
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="text-bold">Report End Period</span>
                                    <span class="pull-right">
                                        {{reportData.dateParams.currentQuarter.name}} {{reportData.dateParams.financialYear}}
                                    </span>
                                </td>
                                <td></td>
                                <td colspan="2">
                                    <span class="text-bold">Report Frequency</span>
                                    <span class="pull-right">{{reportData.workPlan.plan.frequency}}</span>
                                </td>
                                <td></td>
                                <td colspan="2">
                                    <span class="text-bold">Report Date</span>
                                    <span class="pull-right">{{reportData.reportDate}}</span>
                                </td>
                            </tr>
                            <template v-for="directiveAndResolution in reportData.directivesAndResolutions">
                                <tr>
                                    <td colspan="8" style="height: 30px !important;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-bold">Directive/Resolution</td>
                                    <td colspan="6">{{directiveAndResolution.title}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-bold">Responsibility Center</td>
                                    <td colspan="6">{{responsibilityCentre(directiveAndResolution.responsibilityCentreId)}}</td>
                                </tr>
                                <tr class="text-center">
                                    <th>Activity</th>
                                    <th>Output</th>
                                    <th>Measured As</th>
                                    <th>Target</th>
                                    <th>Actual</th>
                                    <th>Achieved (%)</th>
                                    <th>Variance</th>
                                    <th>Comments</th>
                                </tr>
                                <template v-for="activity in directiveAndResolution.activities">
                                    <template v-if="activity.outputs.length > 0">
                                        <tr>
                                            <td :rowspan="activity.outputs.length + 1">{{activity.title}}</td>
                                        </tr>
                                        <template v-for="output in activity.outputs">
                                            <tr>
                                                <td>{{output.title}}</td>
                                                <td class="text-center">
                                                    <span v-if="output.unit === 'percent'">Percentage</span>
                                                    <span v-else>Count</span>
                                                </td>
                                                <td class="text-center">{{output.target}}</td>
                                                <td class="text-center">{{output.actual}}</td>
                                                <td class="text-center">{{output.achieved}}</td>
                                                <td class="text-center">{{output.variance}}</td>
                                                <td>{{output.comments}}</td>
                                            </tr>
                                        </template>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td>{{activity.title}}</td>
                                            <td colspan="7">No outputs!</td>
                                        </tr>
                                    </template>
                                </template>
                            </template>
                        </table>
                    </div>
                </div>
            </template>
            <template v-else>
                <h4>No data!</h4>
            </template>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import SelectBox from "../../shared/SelectBox";
    import Printd  from 'printd';

    export default {
        components: {
            SelectBox,
        },
        created() {
            this.$store.dispatch("GET_PLANS");
            this.$store.dispatch("GET_DIRECTORATES_OPTIONS");
        },
        mounted() {

        },
        data() {
            return {
                isLoading: false,
                filters: {
                    planId: null,
                    workPlanId: null,
                    startDate: null,
                    endDate: null,
                }
            }
        },
        computed: {
            ...mapGetters({
                plans: "PLANS",
                workPlans: "WORK_PLANS",
                activePlan: "ACTIVE_PLAN",
                activeWorkPlan: "ACTIVE_WORK_PLAN",
                directorates: "DIRECTORATES_OPTIONS",
                reportData: "DIR_AND_RES_REPORT_DATA",
            }),
            reportPeriods() {
                if (!this.activePlan) {
                    return [];
                }
                return this.activePlan.reportPeriods || [];
            },
            startDates() {
                return this.reportPeriods.map((period) => period.startDate);
            },
            endDates() {
                return this.reportPeriods.map((period) => period.endDate)
                    .filter((date) => this.$moment(date).isAfter(this.startDate));
            },
            invalidFilters() {
                return this.isLoading || !(
                    !!this.filters.planId &&
                    !!this.filters.workPlanId &&
                    !!this.filters.startDate &&
                    !!this.filters.endDate
                );
            },
            responsibilityCentre(){
                return (directorateId) => {
                    let directorate = this.directorates.find((directorate) => directorate.id === directorateId);
                    return (!!directorate) ? directorate.title : '';
                }
            },
        },
        watch: {
            "filters.workPlanId"(newValue, oldVale) {
                this.setActiveWorkPlan();
            },
        },
        methods: {
            setActivePlan(planId) {
                this.filters.workPlanId = '';
                this.filters.planId = planId;
                let plan = this.plans.find((plan) => plan.id == planId);
                if (!plan) {
                    swal({title: "No strategic plan selected!", icon: 'info', timer: 2000});
                    this.$store.dispatch("SET_ACTIVE_PLAN", null);
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                    this.$store.dispatch("SET_WORK_PLANS", []);
                } else {
                    this.$store.dispatch("SET_ACTIVE_PLAN", plan);
                    this.$store.dispatch("GET_WORK_PLANS", planId);
                }
            },
            setActiveWorkPlan() {
                if (!!!this.filters.workPlanId || (this.activeWorkPlan && this.activeWorkPlan.id === this.filters.workPlanId)) {
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                } else {
                    let workPlan = this.workPlans.find((workPlan) => workPlan.id === this.filters.workPlanId);
                    if (!!workPlan) {
                        this.$store.dispatch("SET_ACTIVE_WORK_PLAN", workPlan);
                        //this.$store.dispatch("GET_DIRECTIVES_AND_RESOLUTIONS", this.filters)
                        this.generateReport();
                    } else {
                        this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                    }
                }
            },
            async generateReport() {
                if (!!this.activePlan) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("LOAD_DIR_AND_RES_REPORT", this.filters);
                        this.isLoading = false;
                    } catch (error) {
                        console.log(error);
                        await swal({title: error, icon: 'error'});
                        this.isLoading = false;
                    }
                }
            },
            downloadPdfReport(){
                if(!!this.reportData.filePath && this.reportData.filePath !== '#'){
                    window.open(this.reportData.filePath,'_blank');
                }
            },
            printReport(){
                let reportElement = document.getElementById('directives-and-resolutions-monitor-report');
                let report = new Printd();
                report.print(reportElement);
            },
        }

    }
</script>

<style scoped>
    .roles-menu {
        margin-top: 10px !important;
    }

    .work-plan {
        margin-top: 25px !important;
    }
    .text-bold{
        font-weight: bolder !important;
    }
</style>
