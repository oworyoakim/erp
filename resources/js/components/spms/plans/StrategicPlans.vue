<template>
    <div class="strategic-plan-form">
        <!-- Default box -->
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <div class="row">
                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
                    <button class="btn btn-primary btn-block" @click="editPlan()">
                        <i class="fa fa-plus"></i> New Strategic Plan
                    </button>
                    <div class="roles-menu">
                        <ul>
                            <li
                                class
                                v-for="plan in plans"
                                :key="plan.id"
                                v-bind:class="{active: !!activePlan && plan.id === activePlan.id}"
                            >
                                <a href="javascript:void(0)" @click="setActivePlan(plan)">{{plan.name}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9 table-responsive">
                    <template v-if="activePlan">
                        <div class="card card-body m-b-5">
                            <h3 class="card-title bb-1">Theme</h3>
                            <div class="card-text" v-html="activePlan.theme"></div>
                        </div>
                        <div class="card card-body m-b-5">
                            <h3 class="card-title bb-1">Vision</h3>
                            <div class="card-text" v-html="activePlan.vision"></div>
                        </div>
                        <div class="card card-body m-b-5">
                            <h3 class="card-title bb-1">Mission</h3>
                            <div class="card-text" v-html="activePlan.mission"></div>
                        </div>
                        <div class="card card-body m-b-5">
                            <h3 class="card-title bb-1">Values</h3>
                            <div class="card-text" v-html="activePlan.values"></div>
                        </div>
                        <div class="m-b-30">
                            <ul class="list-group notification-list">
                                <li class="list-group-item">
                                    Report Frequency:
                                    <div class="status-toggle">{{activePlan.frequency}}</div>
                                </li>
                                <li class="list-group-item">
                                  <span class="pull-left">
                                    Start Date:
                                    <span>{{$moment(activePlan.startDate).format('DD MM, YYYY')}}</span>
                                  </span>
                                                    <span class="pull-right">
                                    End Date:
                                    <span>{{$moment(activePlan.endDate).format('DD MM, YYYY')}}</span>
                                  </span>
                                </li>
                                <li class="list-group-item">
                                    Action:
                                    <div class="status-toggle">
                                        <button class="btn btn-outline-info btn-sm">
                                            <i class="fa fa-upload m-r-5"></i> Upload Document
                                        </button>
                                        <a @click="editPlan(activePlan)" class="btn btn-outline-info btn-sm" href="#">
                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                        </a>
                                        <a
                                            @click="deletePlan(activePlan)"
                                            class="btn btn-outline-danger btn-sm"
                                            href="#"
                                        >
                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="m-b-30 table-responsive">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="card-title m-b-10">Strategic Objectives</h6>
                                </div>
                                <div class="col-auto float-right ml-auto">
                                    <button class="btn btn-primary btn-block m-b-10" @click="editObjective()">
                                        <i class="fa fa-plus"></i> Add Objective
                                    </button>
                                    <!-- <a href="#" class="btn add-btn m-b-10" data-toggle="modal" data-target="#add_promotion"><i class="fa fa-plus"></i> Add Objective</a> -->
                                </div>
                            </div>
                            <app-strategic-objectives :plan-id="activePlan.id"/>
                            <app-objective-form :plan-id="activePlan.id"/>
                        </div>
                        <div class="m-b-30 table-responsive">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="card-title m-b-10">Key Result Areas</h6>
                                </div>
                                <div class="col-auto float-right ml-auto">
                                    <button class="btn btn-primary btn-block m-b-10" @click="editKeyResultArea()">
                                        <i class="fa fa-plus"></i> Add Key Result Area
                                    </button>
                                    <!-- <a href="#" class="btn add-btn m-b-10" data-toggle="modal" data-target="#add_promotion"><i class="fa fa-plus"></i> Add Objective</a> -->
                                </div>
                            </div>
                            <app-key-result-areas :plan-id="activePlan.id"/>
                            <app-key-result-area-form :plan-id="activePlan.id"/>
                        </div>
                        <div class="m-b-30">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h6 class="card-title">SWOT Analysis</h6>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button @click="editSwot()" class="btn btn-info btn-sm mr-2">
                                        <i class="fa fa-plus"></i>
                                        Add Swot
                                    </button>
                                </div>
                            </div>
                            <app-swots-analysis :plan-id="activePlan.id"/>
                            <app-swots-analysis-form :plan-id="activePlan.id"/>
                        </div>
                    </template>
                </div>
            </div>
            <app-plan-form :start-of-next-financial-year="startOfNextFinancialYear"/>
        </template>
    </div>
    <!-- /.card -->
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            title: String,
            startOfNextFinancialYear: String,
        },
        computed: {
            ...mapGetters({
                plans: "PLANS"
            })
        },
        data() {
            return {
                isLoading: false,
                activePlan: null
            };
        },
        created() {
            this.getPlans();
            EventBus.$on(["PLAN_SAVED"], this.getPlans);
        },
        methods: {
            setActivePlan(plan) {
                this.activePlan = plan;
                this.$store.dispatch("SET_ACTIVE_PLAN", plan);
            },

            editPlan(plan = null) {
                EventBus.$emit("EDIT_PLAN", plan);
            },

            async getPlans() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("GET_PLANS");
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                }
            },
            async deletePlan(plan) {
                try {
                } catch (error) {
                    console.error(error);
                }
            },
            editObjective(objective = null) {
                EventBus.$emit("EDIT_OBJECTIVE", objective);
            },
            editKeyResultArea(keyResultArea = null) {
                EventBus.$emit("EDIT_KEY_RESULT_AREA", keyResultArea);
            },
            editSwot(swot = null) {
                EventBus.$emit("EDIT_SWOT", swot);
            },
        }
    };
</script>

<style scoped>
</style>
