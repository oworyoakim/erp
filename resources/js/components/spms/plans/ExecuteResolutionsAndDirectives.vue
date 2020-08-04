<template>
    <div class="strategic-plan-execution">
        <div class="row">
            <div class="col-sm-12 table-responsive">
                <h5>Select a strategic plan to continue</h5>
                <select class="form-control" v-bind:class="{'is-invalid': !!!activePlan}" @change="setActivePlan($event.target.value)">
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
                    <div class="col-sm-12">
                        <h5>Select a work plan to continue</h5>
                        <select class="custom-select" v-model="workPlanId" v-bind:class="{'is-invalid': !!!workPlanId}">
                            <option value="">Select...</option>
                            <option v-for="workPlan in workPlans"
                                    :value="workPlan.id"
                                    :selected="activeWorkPlan && activeWorkPlan.id === workPlan.id"
                                    :key="workPlan.id">
                                {{workPlan.title}}
                            </option>
                        </select>
                    </div>
                </div>
                <template v-if="!!activeWorkPlan">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab_directive_and_resolutions">
                                Resolutions and Directives
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_activities">Activities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_outputs">Outputs</a>
                        </li>
                    </ul>
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Directives and Resolutions Tab -->
                        <div class="tab-pane show active" id="tab_directive_and_resolutions">
                            <DirectivesAndResolutions/>
                        </div>
                        <!-- /Directives and Resolutions Tab -->
                        <!-- Activities Tab -->
                        <div class="tab-pane" id="tab_activities">
                            <DirAndResActivities />
                        </div>
                        <!-- /Activities Tab -->

                        <!-- Outputs Tab -->
                        <div class="tab-pane" id="tab_outputs">
                            <DirAndResActivityOutputs />
                        </div>
                        <!-- /Outputs Tab -->
                    </div>
                </template>
                <template v-else>
                    <h5>No work plan selected!</h5>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import SelectBox from "../../shared/SelectBox";
    import {deepClone} from "../../../utils/helpers";
    import DirectivesAndResolutions from "../directives-and-resolutions/DirectivesAndResolutions";
    import DirAndResActivities from "../directives-and-resolutions/DirAndResActivities";
    import DirAndResActivityOutputs from "../directives-and-resolutions/DirAndResActivityOutputs";

    export default {
        components: {
            DirAndResActivityOutputs,
            DirectivesAndResolutions,
            DirAndResActivities,
            SelectBox,
        },
        created() {
            this.$store.dispatch("GET_PLANS").then((message) => {
                this.getWorkPlans();
            });
        },
        mounted() {

        },
        data() {
            return {
                isLoading: false,
                reportPeriod: '',
                workPlanId: '',
            }
        },
        computed: {
            ...mapGetters({
                plans: "PLANS",
                activePlan: "ACTIVE_PLAN",
                workPlans: "WORK_PLANS",
                activeWorkPlan: "ACTIVE_WORK_PLAN",
            }),
            reportPeriods() {
                if (!this.activePlan) {
                    return [];
                }
                return this.activePlan.reportPeriods;
            },
        },
        watch: {
            workPlanId(newValue, oldVale) {
                this.setActiveWorkPlan();
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
                    return;
                }
                this.$store.dispatch("SET_ACTIVE_PLAN", plan);
                this.getWorkPlans();
            },
            setActiveWorkPlan() {
                if (!!!this.workPlanId || (this.activeWorkPlan && this.activeWorkPlan.id === this.workPlanId)) {
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                    return;
                }
                let workPlan = this.workPlans.find((workPlan) => workPlan.id === this.workPlanId);
                if (!workPlan) {
                    this.$store.dispatch("SET_ACTIVE_WORK_PLAN", null);
                    return;
                }
                this.$store.dispatch("SET_ACTIVE_WORK_PLAN", workPlan);
                this.$nextTick(() => {
                    this.loadDirectivesAndResolutions();
                });
            },
            loadDirectivesAndResolutions() {
                this.$emit("LOAD_DIRECTIVES_AND_RESOLUTIONS");
            },

            getWorkPlans() {
                return this.$store.dispatch("GET_WORK_PLANS", this.activePlan.id);
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
