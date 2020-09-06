<template>
    <div class="strategic-plan-execution">
        <div class="row">
            <div class="col-sm-12 table-responsive">
                <h5>Select a strategic plan to continue</h5>
                <select class="form-control" @change="setActivePlan($event.target.value)">
                    <option value="">Select...</option>
                    <option v-for="plan in plans"
                            :value="plan.id"
                            :selected="activePlan && activePlan.id === plan.id"
                            :key="plan.id">
                        {{plan.name}}
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" v-if="!!activePlan">
                <div class="row my-4">
                    <div class="col-lg-9 col-md-9 col-sm-8">
                        <h5>Select a work plan to continue</h5>
                        <select class="custom-select" v-model="workPlanId">
                            <option value="">Select...</option>
                            <option v-for="workPlan in workPlans"
                                    :value="workPlan.id"
                                    :selected="activeWorkPlan && activeWorkPlan.id === workPlan.id"
                                    :key="workPlan.id">
                                {{workPlan.title}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 text-right">
                        <!-- Add  Work Plan Button -->
                        <button type="button" class="btn btn-primary add-btn mt-1" @click="editWorkPlan()">
                            <i class="fa fa-plus"></i> New Work Plan
                        </button>
                        <!-- /Add  Work Plan Button -->
                    </div>
                </div>
                <template v-if="activeWorkPlan">
                    <div class="m-b-30">
                        <ul class="list-group notification-list">
                            <li class="list-group-item">
                                Theme:
                                <div class="status-toggle">{{activeWorkPlan.theme}}</div>
                            </li>
                            <li class="list-group-item">
                                Description:
                                <div class="status-toggle">{{activeWorkPlan.description}}</div>
                            </li>

                            <li class="list-group-item">
                                <span class="pull-left">Period:</span>
                                <span class="pull-right">
                                    <span>{{$moment(activeWorkPlan.startDate).format('MMM DD, YYYY')}}</span> -
                                    <span>{{$moment(activeWorkPlan.endDate).format('MMM DD, YYYY')}}</span>
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
                    <div class="m-b-30 table-responsive">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab"
                                   href="#tab_activities">Activities</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_stages">Stages</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_tasks">Tasks</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_outputs">Outputs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_indicators">Indicators</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_targets">Targets</a>
                            </li>
                        </ul>
                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Activities Tab -->
                            <div class="tab-pane show active" id="tab_activities">
                                <!-- activities -->
                                <app-activities/>
                                <!-- /activities -->
                            </div>
                            <!-- /Activities Tab -->
                            <!-- Activity Stages Tab -->
                            <div class="tab-pane" id="tab_stages">
                                <!--  stages   -->
                                <app-stages/>
                                <!--/stages   -->
                            </div>
                            <!-- /Activity Stages Tab -->
                            <!-- Stage Tasks Tab -->
                            <div class="tab-pane" id="tab_tasks">
                                <!--  tasks   -->
                                <app-tasks/>
                                <!--//tasks    -->
                            </div>
                            <!-- /Stage Tasks Tab -->
                            <!-- Activity Outputs Tab -->
                            <div class="tab-pane" id="tab_outputs">
                                <!--  Activity Outputs   -->
                                <ActivityOutputs />
                                <!--//Activity Outputs    -->
                            </div>
                            <!-- /Activity Outputs Tab -->
                            <!-- Activity Output Indicators Tab -->
                            <div class="tab-pane" id="tab_indicators">
                                <!--  Activity Output Indicators   -->

                                <!--//Activity Output Indicators    -->
                            </div>
                            <!-- /Activity Output Indicators Tab -->
                            <!-- Activity Output Indicator Targets Tab -->
                            <div class="tab-pane" id="tab_targets">
                                <!--  Activity Output Indicator Targets  -->

                                <!--//Activity Output Indicator Targets    -->
                            </div>
                            <!-- /Activity Output Indicator Targets Tab -->
                        </div>
                    </div>
                </template>
                <template v-else-if="isLoading">
                    <app-spinner></app-spinner>
                </template>
                <template v-else-if="workPlans.length === 0">
                    <div class="alert alert-info text-center">
                        Add a work plan to continue
                    </div>
                </template>
                <WorkPlanForm/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import SelectBox from "../../shared/SelectBox";
    import {deepClone} from "../../../utils/helpers";
    import WorkPlanForm from "../work-plans/WorkPlanForm";
    import ActivityOutputs from "../stages/ActivityOutputs";

    export default {
        components: {
            ActivityOutputs,
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
            ], this.refreshData);
        },
        mounted() {

        },
        data() {
            return {
                isLoading: false,
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
            }),
            activities() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.activeWorkPlan.activities.filter((activity) => {
                    return activity.interventionId === this.interventionId;
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
                this.workPlanId = '';
                let plan = this.plans.find((plan) => plan.id == planId);
                if (!plan) {
                    swal({title: "No strategic plan selected!", icon: 'info', timer: 2000});
                    this.$store.dispatch("SET_ACTIVE_PLAN", null);
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                    this.$store.dispatch("SET_WORK_PLANS", []);
                } else {
                    this.$store.dispatch("SET_ACTIVE_PLAN", plan);
                    this.loadData();
                }
            },
            setActiveWorkPlan() {
                if (!!!this.workPlanId || (this.activeWorkPlan && this.activeWorkPlan.id === this.workPlanId)) {
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                } else {
                    let workPlan = this.workPlans.find((workPlan) => workPlan.id === this.workPlanId);
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", workPlan || null);
                }
            },
            async loadData() {
                if (!!this.activePlan) {
                    try {
                        this.isLoading = true;
                        await this.getWorkPlans();
                        await this.getInterventions();
                        this.isLoading = false;
                    } catch (error) {
                        console.log(error);
                        this.isLoading = false;
                    }
                }
            },
            getWorkPlans() {
                return this.$store.dispatch("GET_WORK_PLANS", this.activePlan.id);
            },
            getInterventions() {
                return this.$store.dispatch("GET_INTERVENTIONS", {planId: this.activePlan.id});
            },
            refreshData() {
                this.$store.dispatch("GET_WORK_PLANS", this.activePlan.id);
            },
            editWorkPlan(workPlan = null) {
                EventBus.$emit("EDIT_WORK_PLAN", workPlan);
            },
            deleteWorkPlan(activeWorkPlan) {

            },
            editActivity(activity = null) {
                EventBus.$emit("EDIT_ACTIVITY", activity);
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
