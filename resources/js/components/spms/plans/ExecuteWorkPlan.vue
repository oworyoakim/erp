<template>
    <div class="strategic-plan-execution">
        <div class="row" v-if="!activeWorkPlan">
            <div class="col-sm-12 table-responsive">
                <h5>Select a strategic plan to continue</h5>
                <select class="form-control" @change="setActivePlan($event.target.value)">
                    <option value="">Select...</option>
                    <option v-for="plan in plans"
                            :value="plan.id"
                            :selected="activePlan && activePlan.id === plan.id"
                            :key="plan.id">
                        {{ plan.name }}
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <template v-if="!!activePlan">
                    <template v-if="isLoading">
                        <app-spinner></app-spinner>
                    </template>
                    <template v-else-if="activity">
                        <ActivityDetails/>
                    </template>
                    <template v-else-if="mainActivity">
                        <MainActivityDetails/>
                    </template>
                    <template v-else-if="activeWorkPlan">
                        <!--          Display some details of the work plan                  -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{ activeWorkPlan.title }}
                                    <button type="button"
                                            @click="setActiveWorkPlan()"
                                            class="btn btn-secondary btn-sm float-right">
                                        <i class="fa fa-backward m-r-5"></i> Back To Work Plans
                                    </button>
                                </h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group notification-list">
                                    <li class="list-group-item">
                                        Theme:
                                        <div class="status-toggle">{{ activeWorkPlan.theme }}</div>
                                    </li>
                                    <li class="list-group-item">
                                        Description:
                                        <div class="status-toggle">{{ activeWorkPlan.description }}</div>
                                    </li>

                                    <li class="list-group-item">
                                        <span class="pull-left">Period:</span>
                                        <span class="pull-right">
                                    <span>{{ $moment(activeWorkPlan.startDate).format('MMM DD, YYYY') }}</span> -
                                    <span>{{ $moment(activeWorkPlan.endDate).format('MMM DD, YYYY') }}</span>
                                </span>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="pull-left">Report Periods:</span>
                                        <span class="pull-right">
                                            <span class="mr-2" v-for="reportPeriod in activeWorkPlan.reportPeriods">
                                                {{reportPeriod.name}},
                                            </span>
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        Action:
                                        <div class="status-toggle">
                                            <button class="btn btn-outline-info btn-sm">
                                                <i class="fa fa-upload m-r-5"></i> Upload Document
                                            </button>
                                            <a href="#"
                                               @click="editWorkPlan(activeWorkPlan)"
                                               class="btn btn-outline-info btn-sm">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a href="#"
                                               @click="deleteWorkPlan(activeWorkPlan)"
                                               class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--          Display Main activities                  -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">
                                    Main Activities
                                    <button type="button"
                                            @click="editMainActivity()"
                                            class="btn btn-success add-btn float-right">
                                        <i class="fa fa-plus m-r-5"></i> Add Main Activity
                                    </button>
                                </h4>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-condensed table-sm">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Quarter</th>
                                        <th>Code</th>
                                        <th>Cost</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="mainActivity in mainActivities" :key="mainActivity.id">
                                        <td>{{ mainActivity.title }}</td>
                                        <td>{{ mainActivity.quarter }}</td>
                                        <td>{{ mainActivity.code }}</td>
                                        <td>{{ mainActivity.cost | currency }}</td>
                                        <td>
                                            <button type="button"
                                                    @click="setActiveMainActivity(mainActivity)"
                                                    class="btn btn-outline-info btn-sm">
                                                <i class="fa fa-info m-r-5"></i> View
                                            </button>
                                            <button type="button"
                                                    @click="editMainActivity(mainActivity)"
                                                    class="btn btn-outline-info btn-sm">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </button>
                                            <button type="button"
                                                    @click="deleteMainActivity(mainActivity)"
                                                    class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <MainActivityForm/>
                    </template>
                    <template v-else>
                        <div class="card mt-2">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Work Plans
                                    <!-- Add  Work Plan Button -->
                                    <button type="button" class="btn btn-primary add-btn mt-1 float-right"
                                            @click="editWorkPlan()">
                                        <i class="fa fa-plus"></i> New Work Plan
                                    </button>
                                    <!-- /Add  Work Plan Button -->
                                </h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-condensed table-sm">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>FinancialYear</th>
                                        <th>StartDate</th>
                                        <th>EndDate</th>
                                        <th>Report Periods</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="workPlan in workPlans" :key="workPlan.id">
                                        <td>{{ workPlan.title }}</td>
                                        <td>{{ workPlan.financialYear }}</td>
                                        <td>{{ $moment(workPlan.startDate).format('MMM DD, YYYY') }}</td>
                                        <td>{{ $moment(workPlan.endDate).format('MMM DD, YYYY') }}</td>
                                        <td>{{ workPlan.reportPeriods.length }}</td>
                                        <td>
                                            <button type="button"
                                                    @click="setActiveWorkPlan(workPlan)"
                                                    class="btn btn-outline-info btn-sm">
                                                <i class="fa fa-info m-r-5"></i> View
                                            </button>
                                            <button type="button"
                                                    @click="editWorkPlan(workPlan)"
                                                    class="btn btn-outline-info btn-sm">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </button>
                                            <button type="button"
                                                    @click="deleteWorkPlan(workPlan)"
                                                    class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                    <WorkPlanForm/>
                </template>
                <template v-else>
                    <h6>No strategic plan selected!</h6>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import {EventBus} from "../../../app";
import SelectBox from "../../shared/SelectBox";
import WorkPlanForm from "../work-plans/WorkPlanForm";
import MainActivityForm from "../activities/MainActivityForm";
import MainActivityDetails from "../activities/MainActivityDetails";
import ActivityDetails from "../activities/ActivityDetails";

export default {
    components: {
        MainActivityForm,
        MainActivityDetails,
        ActivityDetails,
        WorkPlanForm,
        SelectBox,
    },
    created() {
        this.$store.dispatch("GET_PLANS").then(() => {
            this.loadData();
        });
        EventBus.$on([
            'WORK_PLAN_SAVED',
            'ACTIVITY_SAVED',
            'STAGE_SAVED',
            'TASK_SAVED',
        ],() => {
            this.getWorkPlans();
        });
        EventBus.$on(['MAIN_ACTIVITY_SAVED',], () => {
            this.$store.dispatch('GET_MAIN_ACTIVITIES', {workPlanId: this.activeWorkPlan.id}).then(() => {
                //TODO: we need to update the state with the updated active main activity
                if (this.mainActivity) {
                    let mainActivity = this.mainActivities.find((mainActivity) => mainActivity.id === this.mainActivity.id);
                    this.setActiveMainActivity(mainActivity || null);
                }
            });
        });
    },
    mounted() {
        this.$store.dispatch("GET_DIRECTORATES_OPTIONS");
    },
    data() {
        return {
            reportPeriod: '',
            interventionId: '',
            workPlanId: '',
            activityId: '',
            stageId: '',
        }
    },
    computed: {
        ...mapGetters({
            plans: "PLANS",
            activePlan: "ACTIVE_PLAN",
            workPlans: "WORK_PLANS",
            activeWorkPlan: "ACTIVE_WORK_PLAN",
            interventions: "INTERVENTIONS",
            mainActivities: "MAIN_ACTIVITIES",
            mainActivity: 'ACTIVE_MAIN_ACTIVITY',
            activity: 'ACTIVE_ACTIVITY',
            isLoading: "LOADER",
        }),
        activities() {
            if (!this.activeWorkPlan) {
                return [];
            }
            return this.activeWorkPlan.activities.filter((activity) => {
                return activity.interventionId === this.interventionId;
            });
        },
        outputs() {
            return this.activities.map((activity) => {
                return activity.outputs.filter((output) => output.interventionId === this.interventionId);
            });
        },
        interventionsOptions() {
            return this.interventions.map((intervention) => {
                return {
                    text: intervention.name,
                    value: intervention.id,
                }
            });
        },
        activitiesOptions() {
            return this.activities.map((activity) => {
                return {
                    text: activity.title,
                    value: activity.id,
                }
            });
        },
        stages() {
            if (!this.activeWorkPlan) {
                return [];
            }
            return this.workPlan.stages.filter((stage) => {
                return stage.activityId === this.activityId;
            });
        },
        stagesOptions() {
            return this.stages.map((stage) => {
                return {
                    text: stage.title,
                    value: stage.id,
                }
            });
        },
        reportPeriods() {
            if (!this.activePlan) {
                return [];
            }
            return this.activePlan.reportPeriods;
        },
        tasks() {
            if (!this.activeWorkPlan) {
                return [];
            }
            return this.workPlan.tasks.filter((task) => {
                return task.stageId === this.stageId;
            });
        },
    },
    watch: {
        workPlanId(newValue, oldVale) {
            this.setActiveWorkPlan();
            this.interventionId = '';
            this.activityId = '';
        }
    },
    methods: {
        setActivePlan(planId) {
            let plan = this.plans.find((plan) => plan.id == planId);
            if (!plan) {
                swal({title: "No strategic plan selected!", icon: 'info', timer: 2000});
                this.$store.dispatch("SET_ACTIVE_PLAN", null);
                this.$store.commit("SET_ACTIVE_WORK_PLAN", null);
                this.$store.commit("SET_WORK_PLANS", []);
            } else {
                this.$store.commit("SET_ACTIVE_PLAN", plan);
                this.loadData();
            }
        },
        setActiveWorkPlan(workPlan = null) {
            this.$store.dispatch("SET_ACTIVE_WORK_PLAN", workPlan);
            if (workPlan) {
                this.$store.dispatch('GET_MAIN_ACTIVITIES', {workPlanId: workPlan.id});
            }
        },
        async loadData() {
            if (!!this.activePlan) {
                try {
                    this.$store.commit('SET_LOADER', true);
                    await this.getWorkPlans();
                    await this.getInterventions();
                    this.$store.commit('SET_LOADER', false);
                } catch (error) {
                    console.log(error);
                    this.$store.commit('SET_LOADER', false);
                }
            }
        },
        getWorkPlans() {
            return this.$store.dispatch("GET_WORK_PLANS", this.activePlan.id);
        },
        getInterventions() {
            return this.$store.dispatch("GET_INTERVENTIONS", {planId: this.activePlan.id});
        },
        editWorkPlan(workPlan = null) {
            EventBus.$emit("EDIT_WORK_PLAN", workPlan);
        },
        deleteWorkPlan(activeWorkPlan) {

        },
        editActivity(activity = null) {
            EventBus.$emit("EDIT_ACTIVITY", activity);
        },

        editMainActivity(mainActivity = null) {
            EventBus.$emit("EDIT_MAIN_ACTIVITY", mainActivity);
        },
        deleteMainActivity(mainActivity = null) {

        },
        setActiveMainActivity(mainActivity = null) {
            this.$store.commit("SET_ACTIVE_MAIN_ACTIVITY", mainActivity);
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
