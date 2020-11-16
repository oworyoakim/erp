<template>
    <div class="activity-monitor">
        <div class="row">
            <div class="col-sm-3">
                <label>Select a strategic plan to continue</label>
                <select v-model="filters.planId" class="custom-select" @change="setActivePlan()">
                    <option value="">Select...</option>
                    <option v-for="plan in plans"
                            :value="plan.id"
                            :selected="activePlan && activePlan.id === plan.id"
                            :key="plan.id">
                        {{ plan.name }}
                    </option>
                </select>
            </div>
            <div class="col-sm-3" v-if="!!filters.planId">
                <label>Select work plan </label>
                <select class="custom-select" v-model="filters.workPlanId">
                    <option value="">Select...</option>
                    <option v-for="workPlan in workPlans"
                            :value="workPlan.id"
                            :selected="activeWorkPlan && activeWorkPlan.id === workPlan.id"
                            :key="workPlan.id">
                        {{ workPlan.title }}
                    </option>
                </select>
            </div>
            <template v-if="!!filters.workPlanId">
                <div class="col-sm-2">
                    <label>Select directorate </label>
                    <select class="custom-select" v-model="filters.directorateId">
                        <option value="">Select...</option>
                        <option v-for="directorate in directorates"
                                :value="directorate.id"
                                :key="directorate.id">
                            {{ directorate.title }}
                        </option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label>Select Quarter </label>
                    <select class="custom-select" v-model="filters.quarter">
                        <option value="">Select...</option>
                        <option value="Q1">Q1</option>
                        <option value="Q2">Q2</option>
                        <option value="Q3">Q3</option>
                        <option value="Q4">Q4</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-sm btn-secondary mt-4" type="button" @click="printReport()"><i
                        class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-sm btn-primary mt-4" type="button" @click="downloadPdfReport()"><i
                        class="fa fa-file-pdf-o"></i> PDF
                    </button>
                </div>
            </template>
        </div>
        <div class="m-t-5">
            <template v-if="isLoading">
                <app-spinner></app-spinner>
            </template>
            <template v-else-if="!!reportData">
                <div class="row" id="activity-monitor-report">
                    <div class="col-md-12 table-responsive"
                         style="display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table-bordered table-sm"
                               style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                            <tr>
                                <td style="border: 0">
                                    <img :src="'data:image/png;base64,' + reportData.companyLogo"
                                         class="img-fluid small" alt="UNEB-SPMS"/>
                                </td>
                                <td colspan="2" style="border: 0; text-align: center" class="text-center">
                                    <h3>UGANDA NATIONAL EXAMINATIONS BOARD</h3>
                                    <h4 style="font-weight: bold;  text-transform: capitalize;">
                                        PERFORMANCE REPORT FOR THE UNEB {{ reportData.plan }}
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 0;">
                                    <span style="margin-right: 1.5rem; float: left; font-weight: bold;">Report End Period</span>
                                    <span>
                                        {{reportData.dateParams.currentQuarter.name}}
                                        {{ reportData.dateParams.financialYear }}
                                    </span>
                                </td>
                                <td style="border: 0;">
                                    <span style="margin-right: 1.5rem; float: left; font-weight: bold;">Report Frequency</span>
                                    <span>{{ reportData.reportFrequency }}</span>
                                </td>
                                <td style="border: 0">
                                    <span style="margin-right: 1.5rem; float: left; font-weight: bold;">Report Date</span>
                                    <span>{{ reportData.reportDate }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 0;">
                                    <strong style="margin-right: 1.5rem; float: left;">Directorate: </strong>
                                    <span v-if="reportData.directorateId">
                                        {{responsibilityCentre(reportData.directorateId)}}
                                    </span>
                                </td>
                                <td style="border: 0;"></td>
                                <td style="border: 0;">
                                    <strong style="margin-right: 1.5rem; float: left;">Quarter: </strong>
                                    <span v-if="reportData.quarter">
                                        {{reportData.quarter}}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <template v-for="activityBlock in reportData.activityBlocks">
                            <table class="table-bordered table-sm my-5"
                                   style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                                <tr>
                                    <th colspan="8" style="border: 1px solid #dee2e6">
                                        <span style="margin-right: 3rem; float: left;">Main Activity:</span>
                                        <span>{{ activityBlock.name }}</span>
                                    </th>
                                </tr>
                                <template v-if="activityBlock.activities.length > 0">
                                    <template v-for="activity in activityBlock.activities">
                                        <tr>
                                            <th colspan="6" style="border: 1px solid #dee2e6">
                                                <span style="margin-right: 1.5rem; float: left;">Activity:</span>
                                                <span>{{ activity.name }}</span>
                                            </th>
                                            <th colspan="6" style="border: 1px solid #dee2e6">
                                                <span style="margin-right: 1.5rem; float: left;">Estimated Cost:</span>
                                                <span>UGX {{ activity.cost | currency }}</span>
                                            </th>
                                        </tr>
                                        <template v-if="activity.outputs.length > 0">
                                            <tr style="text-align: center;">
                                                <th style="border: 1px solid #dee2e6">Output</th>
                                                <th style="border: 1px solid #dee2e6">Indicator</th>
                                                <th style="border: 1px solid #dee2e6">Measured As</th>
                                                <th style="border: 1px solid #dee2e6">Target</th>
                                                <th style="border: 1px solid #dee2e6">Actual</th>
                                                <th style="border: 1px solid #dee2e6">Achieved (%)</th>
                                                <th style="border: 1px solid #dee2e6">Variance</th>
                                                <th style="border: 1px solid #dee2e6">Comments</th>
                                            </tr>
                                            <template v-for="output in activity.outputs">
                                                <template v-if="output.indicators.length > 0">
                                                    <tr>
                                                        <td :rowspan="output.indicators.length + 1"
                                                            style="border: 1px solid #dee2e6; font-weight: bold;">
                                                            {{ output.name }}
                                                        </td>
                                                    </tr>
                                                    <template v-for="indicator in output.indicators">
                                                        <tr>
                                                            <td style="border: 1px solid #dee2e6">{{ indicator.name }}
                                                            </td>
                                                            <td class="text-center"
                                                                style="border: 1px solid #dee2e6; text-align: center;">
                                                                <span
                                                                    v-if="indicator.unit === 'percent'">Percentage</span>
                                                                <span v-else>Count</span>
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6; text-align: center;">
                                                                {{ indicator.target }}
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6; text-align: center;">
                                                                {{ indicator.actual }}
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6; text-align: center;">
                                                                {{ indicator.achieved }}
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6; text-align: center;">
                                                                {{ indicator.variance }}
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6; width: 25%;">
                                                                {{ indicator.comments }}
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <tr>
                                                        <td style="border: 1px solid #dee2e6;">{{ output.name }}</td>
                                                        <td colspan="7" style="border: 1px solid #dee2e6;">No
                                                            indicators!
                                                        </td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="8" style="border: 1px solid #dee2e6;">No outputs!</td>
                                            </tr>
                                        </template>
                                    </template>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="8" style="border: 1px solid #dee2e6;">No activities!</td>
                                    </tr>
                                </template>
                            </table>
                        </template>
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
import Printd from "printd";

export default {
    components: {
        SelectBox,
    },
    created() {
        this.$store.dispatch("GET_PLANS");
        this.$store.dispatch("GET_DIRECTORATES_OPTIONS");
    },
    data() {
        return {
            isLoading: false,
            filters: {
                planId: '',
                workPlanId: '',
                quarter: '',
                directorateId: '',
            }
        }
    },
    computed: {
        ...mapGetters({
            plans: "PLANS",
            activePlan: "ACTIVE_PLAN",
            workPlans: "WORK_PLANS",
            activeWorkPlan: "ACTIVE_WORK_PLAN",
            directorates: "DIRECTORATES_OPTIONS",
            reportData: "ACTIVITY_REPORT_DATA",
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
        responsibilityCentre(){
            return (directorateId) => {
                let directorate = this.directorates.find((directorate) => directorate.id === directorateId);
                return (!!directorate) ? directorate.title : '';
            }
        },
    },
    watch: {
        "filters.planId"(newValue, oldVale) {
            this.setActivePlan();
        },
        "filters.workPlanId"(newValue, oldVale) {
            this.setActiveWorkPlan();
        },
        "filters.quarter"(newValue, oldVale) {
            this.generateActivityReportData();
        },
        "filters.directorateId"(newValue, oldVale) {
            this.generateActivityReportData();
        },
    },
    methods: {
        setActivePlan() {
            this.filters.workPlanId = '';
            let plan = this.plans.find((plan) => plan.id === this.filters.planId);
            if (!plan) {
                swal({title: "No strategic plan selected!", icon: 'info', timer: 2000});
                this.$store.dispatch("SET_ACTIVE_PLAN", null);
                this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                this.$store.dispatch("SET_WORK_PLANS", []);
            } else {
                this.$store.dispatch("SET_ACTIVE_PLAN", plan);
                this.getWorkPlans();
            }
        },
        setActiveWorkPlan() {
            if (!!!this.filters.workPlanId || (this.activeWorkPlan && this.activeWorkPlan.id === this.filters.workPlanId)) {
                this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
            } else {
                let workPlan = this.workPlans.find((workPlan) => workPlan.id === this.filters.workPlanId);
                this.$store.dispatch("SET_ACTIVE_WORK_PLAN", workPlan || null);
                this.generateActivityReportData();
            }
        },
        getWorkPlans() {
            return this.$store.dispatch("GET_WORK_PLANS", this.activePlan.id);
        },
        async generateActivityReportData() {
            if (!!this.activeWorkPlan) {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("LOAD_ACTIVITY_REPORT_DATA", this.filters);
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    await swal({title: error, icon: 'error'});
                    this.isLoading = false;
                }
            }
        },
        downloadPdfReport() {
            if (!!this.reportData.filePath && this.reportData.filePath !== '#') {
                window.open(this.reportData.filePath, '_blank');
            }
        },
        printReport() {
            let reportElement = document.getElementById('activity-monitor-report');
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
</style>
