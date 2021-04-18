<template>
    <div class="strategic-plan-monitor-summary">
        <div class="row">
            <div class="col-9">
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
            <div class="col-3" v-if="!!filters.planId">
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
                <div class="row" id="strategic-plan-monitor-report-summary">
                    <div class="col-md-12 table-responsive" style="display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table-bordered table-sm" style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                            <tr>
                                <td style="border: 0">
                                    <img :src="'data:image/png;base64,' + reportData.companyLogo" class="img-fluid small" alt="UNEB-SPMS"/>
                                </td>
                                <td colspan="5" style="border: 0; text-align: center" class="text-center">
                                    <h3>UGANDA NATIONAL EXAMINATIONS BOARD</h3>
                                    <h4 style="font-weight: bold;  text-transform: capitalize;">
                                        PERFORMANCE REPORT FOR THE UNEB {{reportData.plan}}
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 0;">
                                    <span style="font-weight: bold;">Report Period</span>
                                    <span style="float: right;">
                                        {{reportData.startDate}} {{reportData.endDate}}
                                    </span>
                                </td>
                                <td colspan="2" style="border: 0;">
                                    <span style="font-weight: bold;">Report Frequency</span>
                                    <span style="float: right;">{{reportData.reportFrequency}}</span>
                                </td>
                                <td colspan="2" style="border: 0">
                                    <span style="font-weight: bold;">Report Date</span>
                                    <span style="float: right">{{reportData.reportDate}}</span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-bordered table-sm" style="width: 100%; border-collapse: collapse; border-spacing: 0; margin-top: 10px;">
                            <template v-if="reportData.outputs.length > 0">
                                <tr style="padding:5px;background-color: #f2f2f2;text-align: left; padding-top:5px">
                                    <th rowspan="3" style="border: 1px solid #dee2e6">Output</th>
                                    <th rowspan="3" style="border: 1px solid #dee2e6">Indicator</th>
                                </tr>
                                <tr>
                                    <template v-for="financialYear in reportData.financialYears">
                                        <th colspan="2" style="border: 1px solid #dee2e6">{{financialYear.name}}</th>
                                    </template>
                                </tr>
                                <tr>
                                    <template v-for="financialYear in reportData.financialYears">
                                        <th style="border: 1px solid #dee2e6">TA</th>
                                        <th style="border: 1px solid #dee2e6">AV</th>
                                    </template>
                                </tr>
                                <template v-for="output in reportData.outputs">
                                    <template v-if="output.indicators.length > 0">
                                        <tr>
                                            <td :rowspan="output.indicators.length + 1"  style="padding:5px;border: 1px solid #dee2e6; font-weight: bold;">{{output.name}}</td>
                                        </tr>
                                        <template v-for="indicator in output.indicators">
                                            <tr>
                                                <td style="padding:5px;font-size: 12px;border: 1px solid #dee2e6">{{indicator.name}}</td>
                                                <template v-for="financialYear in reportData.financialYears">
                                                    <template v-if="indicator.unit == 'count'">
                                                        <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">
                                                            <template v-if="indicator.targets[financialYear.name] > 0">
                                                                {{indicator.targets[financialYear.name]}}
                                                            </template>
                                                        </td>
                                                        <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">
                                                            <template v-if="indicator.actuals[financialYear.name] > 0">
                                                                {{indicator.actuals[financialYear.name]}}
                                                            </template>
                                                        </td>
                                                    </template>
                                                    <template v-else>
                                                        <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">
                                                            <template v-if="indicator.targets[financialYear.name] > 0">
                                                                {{indicator.targets[financialYear.name]}}%
                                                            </template>
                                                        </td>
                                                        <td style="padding:5px;border: 1px solid #dee2e6; text-align: right;">
                                                            <template v-if="indicator.actuals[financialYear.name] > 0">
                                                                {{indicator.actuals[financialYear.name]}}%
                                                            </template>
                                                        </td>
                                                    </template>
                                                </template>
                                            </tr>
                                        </template>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td style="border: 1px solid #dee2e6;">{{output.name}}</td>
                                            <td :colspan="totalColumns - 1"  style="border: 1px solid #dee2e6;">No indicators!</td>
                                        </tr>
                                    </template>
                                </template>
                            </template>
                            <template v-else>
                                <tr>
                                    <td :colspan="totalColumns"  style="border: 1px solid #dee2e6;">No outputs!</td>
                                </tr>
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
                },
            }
        },
        computed: {
            ...mapGetters({
                plans: "PLANS",
                activePlan: "ACTIVE_PLAN",
                reportData: "STRATEGIC_PLAN_REPORT_DATA",
            }),
            totalColumns(){
                if(this.reportData){
                    return 2 + (this.reportData.financialYears.length * 2);
                }
                return 2;
            },
        },
        watch: {
            "filters.planId"(newValue, oldVale) {
                this.setActivePlan();
            },
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
                    this.generateReport();
                }
            },
            async generateReport() {
                if (!!this.filters.planId) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("LOAD_STRATEGIC_PLAN_SUMMARY_REPORT", this.filters);
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
                window.open('/spms/plans/monitor/strategy/summary/excel'+ queryParams,'_blank');
            },
            downloadPdfReport(){
                if(!!this.reportData.filePath && this.reportData.filePath !== '#'){
                    window.open(this.reportData.filePath,'_blank');
                }
            },
            printReport(){
                let reportElement = document.getElementById('strategic-plan-monitor-report-summary');
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
