<template>
    <div class="strategic-plan-execution">
        <div class="page-header row">
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
        <div class="row mt-2">
            <div class="col-sm-12" v-if="!!activePlan">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item">
                        <a @click="loadOutputAchievementsData()" class="nav-link active" data-toggle="tab"
                           href="#tab_output-based-performance-capture">Output-based
                            Performance Capture</a>
                    </li>
                    <li class="nav-item">
                        <a @click="loadOutcomeAchievementsData()" class="nav-link" data-toggle="tab"
                           href="#tab_outcome-based-performance-capture">Outcome-based
                            Performance Capture</a>
                    </li>
                </ul>
                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Output based performance capture Tab -->
                    <div class="tab-pane show active" id="tab_output-based-performance-capture">
                        <OutputAchievementsForm/>
                    </div>
                    <!-- /Output based performance capture Tab -->

                    <!-- Outcome based performance capture Tab -->
                    <div class="tab-pane" id="tab_outcome-based-performance-capture">
                        <OutcomeAchievementsForm/>
                    </div>
                    <!-- /Outcome based performance capture Tab -->
                </div>
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
    import OutputAchievementsForm from "../achievements/OutputAchievementsForm";
    import OutcomeAchievementsForm from "../achievements/OutcomeAchievementsForm";

    export default {
        components: {
            SelectBox,
            OutputAchievementsForm,
            OutcomeAchievementsForm,
        },
        created() {
            this.$store.dispatch("GET_PLANS").then(() => {
                this.loadOutputAchievementsData();
            });
        },
        mounted() {

        },
        data() {
            return {
                isLoading: false,
                reportPeriod: '',
            }
        },
        computed: {
            ...mapGetters({
                plans: "PLANS",
                activePlan: "ACTIVE_PLAN",
            }),
            reportPeriods() {
                if (!this.activePlan) {
                    return [];
                }
                return this.activePlan.reportPeriods;
            },
        },
        methods: {
            setActivePlan(planId) {
                let plan = this.plans.find((plan) => plan.id == planId);
                if (!plan) {
                    swal({title: "No strategic plan selected!", icon: 'info', timer: 2000});
                    this.$store.dispatch("SET_ACTIVE_PLAN", null);
                } else {
                    this.$store.dispatch("SET_ACTIVE_PLAN", plan);
                    this.loadOutputAchievementsData();
                }
            },
            loadOutputAchievementsData() {
                this.$emit("LOAD_OUTPUT_DATA");
            },
            loadOutcomeAchievementsData() {
                this.$emit("LOAD_OUTCOME_DATA");
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
