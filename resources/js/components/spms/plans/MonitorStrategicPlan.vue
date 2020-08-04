<template>
    <div class="strategic-plan-monitor">
        <div class="row">
            <div class="col-sm-5">
                <label>Select a strategic plan to continue</label>
                <select v-model="filters.planId" class="form-control">
                    <option value="">Select...</option>
                    <option v-for="plan in plans"
                            :value="plan.id"
                            :selected="activePlan && activePlan.id === plan.id"
                            :key="plan.id">
                        {{plan.name}}
                    </option>
                </select>
            </div>
            <div class="col-sm-5" v-if="!!filters.planId">
                <label>Select a report period</label>
                <select class="form-control" v-model="filters.reportPeriodId">
                    <option value="">Select...</option>
                    <option v-for="period in reportPeriods"
                            :value="period.id"
                            :key="`report_period_${period.id}`">
                        {{period.name}}
                    </option>
                </select>
            </div>
            <div class="col-sm-2" v-if="!!filters.planId && !!filters.reportPeriodId">
                <button class="btn btn-sm btn-primary mt-4" type="button" @click="downloadReport()"><i
                    class="fa fa-file-excel-o"></i> Excel
                </button>
            </div>
        </div>
        <div class="m-t-5">
            <template v-if="isLoading">
                <app-spinner></app-spinner>
            </template>
            <template v-else-if="!!reportData">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table-bordered table-sm" width="100%">
                            <tr>
                                <td>
                                    <img :src="reportData.companyLogo" class="img-fluid small" alt="UNEB-SPMS"/>
                                </td>
                                <td colspan="7" class="text-center">
                                    <h3>UGANDA NATIONAL EXAMINATIONS BOARD</h3>
                                    <h4 class="text-bold text-uppercase">
                                        PERFORMANCE REPORT FOR THE UNEB {{reportData.plan}}
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
                                    <span class="pull-right">{{reportData.reportFrequency}}</span>
                                </td>
                                <td></td>
                                <td colspan="2">
                                    <span class="text-bold">Report Date</span>
                                    <span class="pull-right">{{reportData.reportDate}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8"></td>
                            </tr>
                            <template v-for="objective in reportData.objectives">
                                <tr>
                                    <th colspan="8">
                                        <span class="mr-5">Strategic Objective:</span>
                                        <span>{{objective.name}}</span>
                                    </th>
                                </tr>
                                <template v-if="objective.interventions.length > 0">
                                    <template v-for="intervention in objective.interventions">
                                        <tr>
                                            <th colspan="8">
                                                <span class="mr-4">Strategic Intervention:</span>
                                                <span>{{intervention.name}}</span>
                                            </th>
                                        </tr>
                                        <template v-if="intervention.outputs.length > 0">
                                            <tr class="text-center">
                                                <th>Output</th>
                                                <th>Indicator</th>
                                                <th>Measured As</th>
                                                <th>Target</th>
                                                <th>Actual</th>
                                                <th>Achieved (%)</th>
                                                <th>Variance</th>
                                                <th>Comments</th>
                                            </tr>
                                            <template v-for="output in intervention.outputs">
                                                <template v-if="output.indicators.length > 0">
                                                    <tr>
                                                        <td :rowspan="output.indicators.length + 1">{{output.name}}</td>
                                                    </tr>
                                                    <template v-for="indicator in output.indicators">
                                                        <tr>
                                                            <td>{{indicator.name}}</td>
                                                            <td class="text-center">
                                                                <span
                                                                    v-if="indicator.unit === 'percent'">Percentage</span>
                                                                <span v-else>Count</span>
                                                            </td>
                                                            <td class="text-center">{{indicator.target}}</td>
                                                            <td class="text-center">{{indicator.actual}}</td>
                                                            <td class="text-center">{{indicator.achieved}}</td>
                                                            <td class="text-center">{{indicator.variance}}</td>
                                                            <td>{{indicator.comments}}</td>
                                                        </tr>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <tr>
                                                        <td>{{output.name}}</td>
                                                        <td colspan="7">No indicators!</td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="8">No outputs!</td>
                                            </tr>
                                        </template>
                                    </template>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="8">No interventions!</td>
                                    </tr>
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
    import {prepareQueryParams} from "../../../utils/helpers";

    export default {
        components: {
            SelectBox,
        },
        created() {
            this.$store.dispatch("GET_PLANS");
        },
        data() {
            return {
                isLoading: false,
                isDownloading: false,
                filters: {
                    planId: '',
                    reportPeriodId: '',
                },
            }
        },
        computed: {
            ...mapGetters({
                plans: "PLANS",
                activePlan: "ACTIVE_PLAN",
                reportData: "STRATEGIC_PLAN_REPORT_DATA",
            }),
            reportPeriods() {
                if (!this.activePlan) {
                    return [];
                }
                return this.activePlan.reportPeriods || [];
            },
        },
        watch: {
            "filters.planId"(newValue, oldVale) {
                this.setActivePlan();
            },
            "filters.reportPeriodId"(newValue, oldVale) {
                this.generateReport();
            }
        },
        methods: {
            setActivePlan() {
                this.filters.reportPeriodId = '';
                let plan = this.plans.find((plan) => plan.id === this.filters.planId);
                if (!plan) {
                    swal({title: "No strategic plan selected!", icon: 'info', timer: 2000});
                    this.$store.dispatch("SET_ACTIVE_PLAN", null);
                } else {
                    this.$store.dispatch("SET_ACTIVE_PLAN", plan);
                }
            },
            async generateReport() {
                if (!!this.filters.planId && !!this.filters.reportPeriodId) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("LOAD_STRATEGIC_PLAN_REPORT", this.filters);
                        this.isLoading = false;
                    } catch (error) {
                        console.log(error);
                        await swal({title: error, icon: 'error'});
                        this.isLoading = false;
                    }
                }
            },
            downloadReport() {
                let queryParams = prepareQueryParams(this.filters);
                window.open('/spms/plans/monitor/strategy/excel'+ queryParams,'_blank');
            }
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
