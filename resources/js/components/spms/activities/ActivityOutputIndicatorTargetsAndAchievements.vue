<template>
    <div class="activity-output-indicator-targets-and-achievements">
        <!--   Filters     -->
        <div class="row mb-2">
            <div class="col-sm-12">
                <label>Report Period</label>
                <select v-model="reportPeriodId" class="custom-select">
                    <option value="">....Select....</option>
                    <option v-for="period in reportPeriods"
                            :value="period.id"
                            :key="period.id">
                        {{period.name}}
                    </option>
                </select>
            </div>
        </div>
        <!--   /Filters     -->
        <div class="mb-2">
            <template v-if="isLoading">
                <Spinner/>
            </template>
            <template v-else>
                <form @submit.prevent="saveActivityPerformance">
                    <div class="my-2" v-if="!isFetching && activityPerformanceChanged">
                        <button type="submit"
                                :disabled="isSending"
                                class="btn btn-primary btn-block pull-right mb-4">
                            <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                            <span>Save</span>
                        </button>
                    </div>

                    <div class="my-2">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="bg-light">
                                <th>Output</th>
                                <th>Indicator</th>
                                <th>Target</th>
                                <th>Actual</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="isFetching">
                                <tr>
                                    <td colspan="4">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <template v-for="output in activityPerformance">
                                    <template v-if="output.indicators.length === 0">
                                        <tr>
                                            <td>{{output.name}}</td>
                                            <td colspan="3">No indicators</td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td :rowspan="output.indicators.length + 1">
                                                {{output.name}}
                                            </td>
                                        </tr>
                                        <template v-for="indicator in output.indicators">
                                            <tr>
                                                <td>{{indicator.name}}</td>
                                                <td>
                                                    <template v-if="indicator.unit === 'percent'">
                                                        <div class="input-group input-group-sm">
                                                            <input class="form-control form-control-sm text-right"
                                                                   v-model="indicator.target"
                                                                   :readonly="$moment().isAfter(activeWorkPlan.planningDeadline)">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template v-else>
                                                        <input class="form-control form-control-sm text-right"
                                                               v-model="indicator.target"
                                                               :readonly="$moment().isAfter(activeWorkPlan.planningDeadline)">
                                                    </template>
                                                </td>
                                                <td class="text-right">
                                                    <template v-if="indicator.unit === 'percent'">
                                                        <div class="input-group input-group-sm">
                                                            <input class="form-control form-control-sm text-right"
                                                                   v-model="indicator.achievement"
                                                                   :readonly="$moment().isSameOrBefore(activeWorkPlan.planningDeadline)">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template v-else>
                                                        <input class="form-control form-control-sm text-right"
                                                               v-model="indicator.achievement"
                                                               :readonly="$moment().isSameOrBefore(activeWorkPlan.planningDeadline)">
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                </template>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </form>
            </template>
        </div>
    </div>
</template>

<script>
    import Spinner from "../../shared/Spinner";
    import {mapGetters} from "vuex";

    export default {
        components: {Spinner},
        data() {
            return {
                reportPeriodId: '',
                activityPerformance: [],
                isFetching: false,
                isSending: false,
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                activePlan: 'ACTIVE_PLAN',
                activeWorkPlan: "ACTIVE_WORK_PLAN",
                oldActivityPerformance: "ACTIVITY_PERFORMANCE",
                activity: "ACTIVE_ACTIVITY",
            }),
            reportPeriods() {
                if (!this.activePlan || !this.activeWorkPlan) {
                    return [];
                }
                return this.activePlan.reportPeriods.filter((period) => {
                    return this.$moment(period.startDate).isSameOrAfter(this.activeWorkPlan.startDate) &&
                        this.$moment(period.endDate).isSameOrBefore(this.activeWorkPlan.endDate);
                });
            },
            activitiesOptions() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.activeWorkPlan.activities.filter((activity) => {
                    return activity.interventionId === this.interventionId;
                }).map((activity) => {
                    return {
                        text: activity.title,
                        value: activity.id,
                    }
                });
            },
            activityPerformanceChanged() {
                return !this.$isEqual(this.activityPerformance, this.oldActivityPerformance);
            },
        },
        watch: {
            reportPeriodId(newValue, oldValue) {
                this.getActivityPerformance();
            },
        },
        methods: {
            async getActivityPerformance() {
                try {
                    if (!!this.reportPeriodId) {
                        this.activityPerformance = await this.$store.dispatch("GET_ACTIVITY_PERFORMANCE", {
                            activityId: this.activity.id,
                            reportPeriodId: this.reportPeriodId,
                        });
                    }
                } catch (error) {
                    console.log(error);
                }
            },
            async saveActivityPerformance() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_ACTIVITY_PERFORMANCE", {
                        performance: this.activityPerformance,
                        reportPeriodId: this.reportPeriodId,
                    });
                    this.isSending = false;
                    toastr.success(response);
                   this.getActivityPerformance();
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
        },
    }
</script>

<style scoped>

</style>
