<template>
    <app-spinner v-if="isLoading || !objective"/>
    <div class="mt-2" v-else>
        <!-- Page Tab -->
        <div class="page-menu">
            <div class="row">
                <div class="col-sm-12">
                    <h3>
                        {{objective.name}}
                        <button @click="editObjective()" class="btn btn-primary add-btn btn-sm pull-right"><i
                            class="fa fa-pencil"></i> Edit Objective
                        </button>
                    </h3>
                    <h5>Description</h5>
                    <p class="text-muted"> {{objective.description}}</p>
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab_interventions">Interventions</a>
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
                    <!-- objective form -->
                    <app-objective-form :plan-id="objective.planId"/>
                    <!-- /objective form -->
                </div>
            </div>
        </div>
        <!-- /Page Tab -->

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Interventions Tab -->
            <div class="tab-pane show active" id="tab_interventions">
                <!-- Add Intervention Button -->
                <div class="text-right mb-4 clearfix">
                    <button @click="editIntervention()" class="btn btn-primary add-btn" type="button"><i
                        class="fa fa-plus"></i> Add Intervention
                    </button>
                </div>
                <!-- /Add Intervention Button -->
                <!-- interventions -->
                <app-interventions :interventions="interventions"/>
                <!-- /interventions -->
                <!-- intervention form -->
                <app-intervention-form/>
                <!-- /intervention form -->
            </div>
            <!-- /Interventions Tab -->

            <!-- Outputs Tab -->
            <div class="tab-pane" id="tab_outputs">
                <div class="row mb-4">
                    <div class="col-lg-9 col-md-9 col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Intervention</span>
                            </div>
                            <select class="custom-select" v-model="interventionId">
                                <option value="">Select...</option>
                                <option v-for="interv in interventionsOptions" :value="interv.value"
                                        :key="interv.value">{{interv.text}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 text-right">
                        <!-- Add Output Button -->
                        <button @click="editOutput()" class="btn btn-primary add-btn mt-2" type="button"><i
                            class="fa fa-plus"></i> Add Output
                        </button>
                        <!-- /Add Output Button -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- outputs -->
                        <app-outputs :outputs="outputs"/>
                        <!-- /outputs -->
                        <!-- output form -->
                        <app-output-form/>
                        <!-- /output form -->
                    </div>
                </div>
            </div>
            <!-- /Outputs Tab -->

            <!-- Output Indicators Tab -->
            <div class="tab-pane" id="tab_indicators">
                <div class="row mb-4">
                    <div class="col-md-4 col-sm-6">
                        <label>Intervention</label>
                        <select class="custom-select" v-model="interventionId">
                            <option value="">Select...</option>
                            <option v-for="interv in interventionsOptions"
                                    :value="interv.value"
                                    :key="interv.value">
                                {{interv.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label>Output</label>
                        <select class="custom-select" v-model="outputId">
                            <option value="">Select...</option>
                            <option v-for="output in outputsOptions"
                                    :value="output.value"
                                    :key="output.value">
                                {{output.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 text-right">
                    <!-- Add Indicator Button -->
                        <button @click="editOutputIndicator()" class="btn btn-primary add-btn mt-2" type="button"><i
                            class="fa fa-plus"></i> Add Output Indicator
                        </button>
                    <!-- /Add Indicator Button -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- indicators -->
                        <app-output-indicators :indicators="indicators"/>
                        <!-- /indicators -->
                        <!-- indicator form -->
                        <app-output-indicator-form/>
                        <!-- /indicator form -->
                    </div>
                </div>
            </div>
            <!-- /Output Indicators Tab -->

            <!-- Output Indicator Targets Tab -->
            <div class="tab-pane" id="tab_targets">
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <label>Intervention</label>
                        <select class="custom-select" v-model="interventionId">
                            <option value="">Select...</option>
                            <option v-for="interv in interventionsOptions"
                                    :value="interv.value"
                                    :key="interv.value">
                                {{interv.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <label>Output</label>
                        <select class="custom-select" v-model="outputId">
                            <option value="">Select...</option>
                            <option v-for="output in outputsOptions"
                                    :value="output.value"
                                    :key="output.value">
                                {{output.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <label>Indicator</label>
                        <select class="custom-select" v-model="outputIndicatorId">
                            <option value="">Select...</option>
                            <option v-for="indicator in indicatorsOptions"
                                    :value="indicator.value"
                                    :key="indicator.value">
                                {{indicator.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 text-right">
                        <!-- Add Target Button -->
                        <button @click="editOutputIndicatorTarget()" class="btn btn-primary add-btn mt-2" type="button"><i
                            class="fa fa-plus"></i> Add Output Indicator
                        </button>
                        <!-- /Add Target Button -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- targets -->
                        <app-output-indicator-targets :targets="targets"/>
                        <!-- /targets -->
                        <!-- target form -->
                        <app-output-indicator-target-form/>
                        <!-- /target form -->
                    </div>
                </div>
            </div>
            <!-- /Output Indicator Targets Tab -->
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            objectiveId: String,
            startOfNextFinancialYear: String,
        },
        created() {
            this.getObjectiveDetails();
            EventBus.$on([
                'OBJECTIVE_SAVED',
                'INTERVENTION_SAVED',
                'OUTPUT_SAVED',
                'OUTPUT_INDICATOR_SAVED',
                'OUTPUT_INDICATOR_TARGET_SAVED',
            ], () => {
                this.$store.dispatch('GET_OBJECTIVE_DETAILS', this.objectiveId);
            });
        },
        data() {
            return {
                interventionId: '',
                outputId: '',
                outputIndicatorId: '',
                isLoading: false,
            }
        },
        watch:{
            interventionId(newValue,oldValue){
                this.outputId = '';
                this.outputIndicatorId = '';
            },
            outputId(newValue,oldValue){
                this.outputIndicatorId = '';
            }
        },
        computed: {
            ...mapGetters({
                objective: 'OBJECTIVE_DETAILS',
            }),
            interventions() {
                return this.objective.interventions || [];
            },
            outputs() {
                return this.objective.outputs.filter((output) => {
                    return output.interventionId === this.interventionId;
                })
            },
            interventionsOptions() {
                return this.objective.interventions.map((intervention) => {
                    return {
                        text: intervention.name,
                        value: intervention.id,
                    }
                });
            },
            outputsOptions() {
                return this.outputs.map((output) => {
                    return {
                        text: output.name,
                        value: output.id,
                    }
                });
            },
            indicators() {
                return this.objective.indicators.filter((indicator) => {
                    return indicator.outputId === this.outputId;
                });
            },
            indicatorsOptions() {
                return this.indicators.map((indicator) => {
                    return {
                        text: indicator.name,
                        value: indicator.id,
                    }
                });
            },
            targets() {
                return this.objective.targets.filter((target) => {
                    return target.outputIndicatorId === this.outputIndicatorId;
                });
            }
        },
        methods: {
            async getObjectiveDetails() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_OBJECTIVE_DETAILS', this.objectiveId);
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    await swal({title: error, icon: 'error'});
                    this.isLoading = false;
                }
            },
            editObjective() {
                EventBus.$emit("EDIT_OBJECTIVE", this.objective);
            },
            editIntervention(intervention = null) {
                EventBus.$emit("EDIT_INTERVENTION", intervention);
            },
            editOutput(output = null) {
                EventBus.$emit("EDIT_OUTPUT", output);
            },

            editOutputIndicator(indicator = null) {
                EventBus.$emit("EDIT_OUTPUT_INDICATOR", indicator);
            },

            editOutputIndicatorTarget(target = null) {
                EventBus.$emit("EDIT_OUTPUT_INDICATOR_TARGET", target);
            },
        }
    }
</script>

<style scoped>

</style>
