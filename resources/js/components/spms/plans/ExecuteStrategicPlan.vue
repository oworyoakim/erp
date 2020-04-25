<template>
    <div class="strategic-plan-execution">
        <div class="row">
            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <div class="row">
                    <div class="col-sm-12">
                        <h5>Select a strategic plan to continue</h5>
                        <select class="form-control" @change="setActivePlan($event.target.value)">
                            <option value="">Select...</option>
                            <option v-for="plan in plans"
                                    :value="plan.id"
                                    :selected="activePlan && activePlan.id === plan.id"
                                    :key="plan.id"
                            >
                                {{plan.name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row" v-if="!!activePlan">
                    <div class="col-sm-12">
                        <app-spinner v-if="isLoading"/>
                        <template v-else>
                            <template v-if="workPlans.length > 0">
                                <h5 class="mt-4">Work Plans</h5>
                                <div class="roles-menu">
                                    <ul>
                                        <li
                                            class
                                            v-for="workPlan in workPlans"
                                            :key="workPlan.id"
                                            v-bind:class="{active: !!activeWorkPlan && workPlan.id === activeWorkPlan.id}"
                                        >
                                            <a href="javascript:void(0)" @click="setActiveWorkPlan(workPlan)">{{workPlan.title}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                            <button class="btn btn-primary btn-block mt-4" @click="editWorkPlan()">
                                <i class="fa fa-plus"></i> New Work Plan
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9 table-responsive">
                <div class="work-plan">
                    <template v-if="!!activePlan">
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
                                               class="btn btn-outline-info btn-sm"
                                            >
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a href="#"
                                               @click="deleteWorkPlan(activeWorkPlan)"
                                               class="btn btn-outline-danger btn-sm"
                                            >
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
                                        <!--  //TODO: implement this   -->
                                    </div>
                                    <!-- /Stage Tasks Tab -->
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div v-if="workPlans.length > 0" class="alert alert-info text-center">
                                Click a work plan to execute
                            </div>
                            <div v-else class="alert alert-info text-center">
                                Add a work plan to continue
                            </div>
                        </template>
                        <app-work-plan-form/>
                    </template>
                    <template v-else>
                        <div class="alert alert-info text-center">
                            No strategic plan selected
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        created() {
            this.$store.dispatch("GET_PLANS");
            EventBus.$on([
                'WORK_PLAN_SAVED',
                'ACTIVITY_SAVED',
                'STAGE_SAVED',
                'TASK_SAVED',
            ], this.refreshData);
        },
        data() {
            return {
                isLoading: false,
                activePlan: null,
                interventionId: '',
                activityId: '',
                stageId: '',
            }
        },
        computed: {
            ...mapGetters({
                plans: "PLANS",
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
            tasks() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.workPlan.tasks.filter((task) => {
                    return task.stageId === this.stageId;
                });
            }
        },
        methods: {
            setActivePlan(planId) {
                let plan = this.plans.find((plan) => {
                    return plan.id == planId;
                });
                if (plan) {
                    this.setActiveWorkPlan(null);
                    this.activePlan = plan;
                    this.$store.dispatch("SET_ACTIVE_PLAN", plan);
                    this.getWorkPlans();
                    this.getInterventions();
                } else {
                    swal({title: "No strategic plan selected!", icon: 'info', timer: 2000});
                    this.activePlan = null;
                    this.$store.dispatch("SET_ACTIVE_PLAN", null);
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                }
            },
            setActiveWorkPlan(workPlan) {
                //this.activeWorkPlan = workPlan;
                this.$store.dispatch("SET_ACTIVE_WORK_PLAN", workPlan);
            },
            async getWorkPlans() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("GET_WORK_PLANS", this.activePlan.id);
                    this.isLoading = false;
                } catch (error) {
                    this.isLoading = false;
                    console.error(error);
                }
            },
            refreshData() {
                this.$store.dispatch("GET_WORK_PLANS", this.activePlan.id);
            },
            getInterventions() {
                this.$store.dispatch("GET_INTERVENTIONS", {planId: this.activePlan.id});
            },
            editWorkPlan(workPlan = null) {
                EventBus.$emit("EDIT_WORK_PLAN", workPlan);
            },
            deleteWorkPlan(activeWorkPlan) {

            },
            editActivity(activity = null) {
                EventBus.$emit("EDIT_ACTIVITY", activity);
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
