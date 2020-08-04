<template>
    <div class="activity-monitor">
        <div class="row">
            <div class="col-sm-4">
                <label>Select a strategic plan to continue</label>
                <select v-model="filters.planId" class="form-control" @change="setActivePlan()">
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
                <label>Select start date</label>
                <select class="form-control" v-model="filters.startDate">
                    <option value="">Select...</option>
                    <option v-for="date in startDates"
                            :value="date"
                            :key="`start_date_${date}`">
                        {{date}}
                    </option>
                </select>
            </div>
            <div class="col-sm-4" v-if="!!filters.startDate">
                <label>Select end date</label>
                <select class="form-control" v-model="filters.endDate">
                    <option value="">Select...</option>
                    <option v-for="date in endDates"
                            :value="date"
                            :key="`end_date_${date}`">
                        {{date}}
                    </option>
                </select>
            </div>

        </div>
        <template v-if="isLoading || !activePlan">
            <app-spinner></app-spinner>
        </template>
        <template v-else>
            <div class="m-t-5">
                <h4>Coming Soon!</h4>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import SelectBox from "../../shared/SelectBox";

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
                filters: {
                    planId: '',
                    workPlanId: '',
                    interventionId: '',
                    activityId: '',
                    startDate: '',
                    endDate: '',
                }
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
            startDates() {
                return this.reportPeriods.map((period) => period.startDate);
            },
            endDates() {
                return this.reportPeriods.map((period) => period.endDate)
                    .filter((date) => this.$moment(date).isAfter(this.startDate));
            }
        },
        watch: {
            "filters.planId"(newValue, oldVale) {
                this.setActivePlan();
            },
            "filters.workPlanId"(newValue, oldVale) {
                this.setActiveWorkPlan();
            }
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
                }
            },
            setActiveWorkPlan() {
                if (!!!this.filters.workPlanId || (this.activeWorkPlan && this.activeWorkPlan.id === this.filters.workPlanId)) {
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                } else {
                    let workPlan = this.workPlans.find((workPlan) => workPlan.id === this.filters.workPlanId);
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", workPlan || null);
                }
            },
            async loadData() {
                if (!!this.activePlan) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("LOAD_STRATEGIC_PLAN_REPORT", this.filters);
                        this.isLoading = false;
                    } catch (error) {
                        console.log(error);
                        await swal({title: error,icon: 'error'});
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
