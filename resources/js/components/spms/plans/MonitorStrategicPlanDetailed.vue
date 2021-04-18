<template>
    <div class="strategic-plan-monitor-detailed">
        <div class="row">
            <div class="col-sm-4">
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
            <div class="col-sm-4" v-if="!!filters.planId">
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
            <div class="col-sm-4" v-if="!!filters.planId && !!filters.reportPeriodId">
                <button class="btn btn-sm btn-secondary mt-4" type="button" @click="printReport()"><i
                    class="fa fa-print"></i> Print
                </button>
                <button class="btn btn-sm btn-primary mt-4" type="button" @click="downloadPdfReport()"><i
                    class="fa fa-file-pdf-o"></i> PDF
                </button>
            </div>
        </div>
        <div class="m-t-5">
            <template v-if="isLoading">
                <app-spinner></app-spinner>
            </template>
            <template v-else-if="!!reportData">
                <div class="row" id="strategic-plan-monitor-report">
                    <div class="col-md-12 table-responsive" style="display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table-bordered table-sm" style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                            <tr>
                                <td style="border: 0">
                                    <img :src="'data:image/png;base64,' + reportData.companyLogo" class="img-fluid small" alt="UNEB-SPMS"/>
                                </td>
                                <td colspan="7" style="border: 0; text-align: center" class="text-center">
                                    <h3>UGANDA NATIONAL EXAMINATIONS BOARD</h3>
                                    <h4 style="font-weight: bold;  text-transform: capitalize;">
                                        PERFORMANCE REPORT FOR THE UNEB {{reportData.plan}}
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 0;">
                                    <span style="font-weight: bold;">Report End Period</span>
                                    <span style="float: right;">
                                        {{reportData.dateParams.currentQuarter.name}} {{reportData.dateParams.financialYear}}
                                    </span>
                                </td>
                                <td style="border: 0;"></td>
                                <td colspan="2" style="border: 0;">
                                    <span style="font-weight: bold;">Report Frequency</span>
                                    <span style="float: right;">{{reportData.reportFrequency}}</span>
                                </td>
                                <td style="border: 0"></td>
                                <td colspan="2" style="border: 0">
                                    <span style="font-weight: bold;">Report Date</span>
                                    <span style="float: right">{{reportData.reportDate}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="border: 0; height: 10px;"></td>
                            </tr>



                            <template v-for="objective in reportData.objectives">
                                <tr>
                                    <th colspan="8" style="border: 1px solid #dee2e6; background-color:#d1d1d1;">
                                        <span style="margin-right: 3rem; float: left;">Strategic Objective: </span>
                                        <span style="font-weight: normal;">{{objective.name}}</span>
                                    </th>
                                </tr>
                                <template v-if="objective.interventions.length > 0">
                                    <template v-for="intervention in objective.interventions">
<!--                                        <tr>-->
<!--                                            <th colspan="8" style="border: 1px solid #dee2e6">-->
<!--                                                <span style="margin-right: 1.5rem; float: left;">Strategic Intervention:</span>-->
<!--                                                <span>{{intervention.name}}</span>-->
<!--                                            </th>-->
<!--                                        </tr>-->
                                        <template v-if="intervention.outputs.length > 0">
                                            <tr style="padding:5px;background-color: #f2f2f2;text-align: left;padding-top:5px">
                                                <th style="border: 1px solid #dee2e6">Output</th>
                                                <th style="border: 1px solid #dee2e6">Indicator</th>
                                                <th style="border: 1px solid #dee2e6">MA</th>
                                                <th style="border: 1px solid #dee2e6">TA</th>
                                                <th style="border: 1px solid #dee2e6">AV</th>
                                                <th style="border: 1px solid #dee2e6">PA%</th>
                                                <th style="border: 1px solid #dee2e6">VA</th>
                                                <th style="border: 1px solid #dee2e6">Comments</th>
                                            </tr>
                                            <template v-for="output in intervention.outputs">
                                                <template v-if="output.indicators.length > 0">
                                                    <tr>
                                                        <td :rowspan="output.indicators.length + 1"  style="padding:5px;border: 1px solid #dee2e6; font-weight: bold;">{{output.name}}</td>
                                                    </tr>
                                                    <template v-for="indicator in output.indicators">
                                                        <tr>
                                                            <td style="padding:5px;font-size: 12px;border: 1px solid #dee2e6">{{indicator.name}}</td>
                                                            <td class="text-center" style="border: 1px solid #dee2e6; text-align: center;">
                                                                <span
                                                                    v-if="indicator.unit === 'percent'">%age</span>
                                                                <span v-else>CT</span>
                                                            </td>
                                                            <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">{{indicator.target}}</td>
                                                            <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">{{indicator.actual}}</td>
                                                            <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">{{indicator.achieved}}</td>
                                                            <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">{{indicator.variance}}</td>
                                                            <td style="padding:5px;font-size: 12px;border: 1px solid #dee2e6; width: 25%;">{{indicator.comments}}</td>
                                                        </tr>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <tr>
                                                        <td style="border: 1px solid #dee2e6;">{{output.name}}</td>
                                                        <td colspan="7"  style="border: 1px solid #dee2e6;">No indicators!</td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="8"  style="border: 1px solid #dee2e6;">No outputs!</td>
                                            </tr>
                                        </template>
                                    </template>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="8"  style="border: 1px solid #dee2e6;">No interventions!</td>
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
    import Printd  from 'printd';

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
            downloadExcelReport() {
                let queryParams = prepareQueryParams(this.filters);
                window.open('/spms/plans/monitor/strategy/excel'+ queryParams,'_blank');
            },
            downloadPdfReport(){
                if(!!this.reportData.filePath && this.reportData.filePath !== '#'){
                    window.open(this.reportData.filePath,'_blank');
                }
            },
            printReport(){
                let reportElement = document.getElementById('strategic-plan-monitor-report');
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
