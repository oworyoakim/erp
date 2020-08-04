<template>
    <div class="strategic-plan-monitor">
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
            <div class="col-sm-4">
                <template v-if="!!filters.workPlanId">
                    <label>Select a directive/resolution</label>
                    <select v-model="filters.directiveAndResolutionId" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="dirAndRes in directivesAndResolutions"
                                :value="dirAndRes.id"
                                :key="dirAndRes.id">
                            {{dirAndRes.title}}
                        </option>
                    </select>
                </template>
            </div>
        </div>

        <div class="mt-3">
            <template v-if="isLoading">
                <app-spinner></app-spinner>
            </template>
            <template v-else-if="!!reportData">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table-bordered table-sm" width="100%">
                            <tr>
                                <td>
                                    <img src="/storage/images/logo2.png" class="img-fluid small" alt="UNEB-SPMS"/>
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
                            <tr>
                                <td colspan="4">
                                    <span class="text-bold">Responsibility Center</span>
                                    <template v-if="!!responsibilityCentre">
                                        <span class="pull-right">{{responsibilityCentre.title}}</span>
                                    </template>
                                </td>
                                <td colspan="4">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-bold">Directive/Resolution</td>
                                <td colspan="6">{{reportData.title}}</td>
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
                            <template v-for="activity in reportData.activities">
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
    import {EventBus} from "../../../app";
    import SelectBox from "../../shared/SelectBox";
    import {deepClone} from "../../../utils/helpers";

    export default {
        components: {
            SelectBox,
        },
        created() {
            this.$store.dispatch("GET_PLANS");
        },
        mounted() {

        },
        data() {
            return {
                responsibilityCentre: null,
                isLoading: false,
                filters: {
                    planId: null,
                    workPlanId: null,
                    directiveAndResolutionId: null,
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
                directivesAndResolutions: "DIRECTIVES_AND_RESOLUTIONS",
                reportData: "DIR_AND_RES_REPORT_DATA",
            }),
            reportPeriods() {
                if (!this.activePlan) {
                    return [];
                }
                return this.activePlan.reportPeriods || [];
            },
            directiveAndResolution() {
                return this.directivesAndResolutions.find((dirAndRes) => dirAndRes.id === this.filters.directiveAndResolutionId) || null
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
                    !!this.filters.directiveAndResolutionId &&
                    !!this.filters.startDate &&
                    !!this.filters.endDate
                );
            }
        },
        watch: {
            "filters.workPlanId"(newValue, oldVale) {
                this.setActiveWorkPlan();
            },
            "filters.directiveAndResolutionId"(newValue, oldVale) {
                if (!!this.filters.directiveAndResolutionId) {
                    this.generateReport();
                }
            }
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
                        this.$store.dispatch("GET_DIRECTIVES_AND_RESOLUTIONS", this.filters)
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
                        if (this.directiveAndResolution) {
                            this.responsibilityCentre = await this.$store.dispatch("GET_RESPONSIBILITY_CENTER", this.directiveAndResolution.responsibilityCentreId);
                        }
                        this.isLoading = false;
                    } catch (error) {
                        console.log(error);
                        await swal({title: error, icon: 'error'});
                        this.isLoading = false;
                    }
                }
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
