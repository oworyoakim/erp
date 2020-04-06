<template>
    <app-spinner v-if="isLoading || !keyResultArea"/>
    <div class="mt-2" v-else>
        <!-- Page Tab -->
        <div class="page-menu">
            <div class="row">
                <div class="col-sm-12">
                    <h3>
                        {{keyResultArea.name}}
                        <button @click="editKeyResultArea()" class="btn btn-primary add-btn btn-sm pull-right"><i
                            class="fa fa-pencil"></i> Edit Key Result Area
                        </button>
                    </h3>
                    <h5>Description</h5>
                    <p class="text-muted"> {{keyResultArea.description}}</p>
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab_outcomes">Outcomes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_indicators">Indicators</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_targets">Targets</a>
                        </li>
                    </ul>
                    <!-- key result area form -->
                    <app-key-result-area-form :plan-id="keyResultArea.planId"/>
                    <!-- /key result area form -->
                </div>
            </div>
        </div>
        <!-- /Page Tab -->

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Outcomes Tab -->
            <div class="tab-pane show active" id="tab_outcomes">
                <div class="row mb-4">
                    <div class="col-sm-12 text-right">
                        <!-- Add Outcome Button -->
                        <button @click="editOutcome()" class="btn btn-primary add-btn mt-2" type="button"><i
                            class="fa fa-plus"></i> Add Outcome
                        </button>
                        <!-- /Add Outcome Button -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- outcomes -->
                        <app-outcomes :outcomes="outcomes"/>
                        <!-- /outcomes -->
                        <!-- outcome form -->
                        <app-outcome-form/>
                        <!-- /outcome form -->
                    </div>
                </div>
            </div>
            <!-- /Outcomes Tab -->

            <!-- Outcome Indicators Tab -->
            <div class="tab-pane" id="tab_indicators">
                <div class="row mb-4">
                    <div class="col-sm-8">
                        <label>Outcome</label>
                        <select class="custom-select" v-model="outcomeId">
                            <option value="">Select...</option>
                            <option v-for="outcome in outcomesOptions"
                                    :value="outcome.value"
                                    :key="outcome.value">
                                {{outcome.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-4 text-right">
                    <!-- Add Indicator Button -->
                        <button @click="editOutcomeIndicator()" class="btn btn-primary add-btn mt-2" type="button"><i
                            class="fa fa-plus"></i> Add Outcome Indicator
                        </button>
                    <!-- /Add Indicator Button -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- indicators -->
                        <app-outcome-indicators :indicators="indicators"/>
                        <!-- /indicators -->
                        <!-- indicator form -->
                        <app-outcome-indicator-form/>
                        <!-- /indicator form -->
                    </div>
                </div>
            </div>
            <!-- /Outcome Indicators Tab -->

            <!-- Outcome Indicator Targets Tab -->
            <div class="tab-pane" id="tab_targets">
                <div class="row mb-4">
                    <div class="col-md-4 col-sm-6">
                        <label>Outcome</label>
                        <select class="custom-select" v-model="outcomeId">
                            <option value="">Select...</option>
                            <option v-for="outcome in outcomesOptions"
                                    :value="outcome.value"
                                    :key="outcome.value">
                                {{outcome.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label>Indicator</label>
                        <select class="custom-select" v-model="outcomeIndicatorId">
                            <option value="">Select...</option>
                            <option v-for="indicator in indicatorsOptions"
                                    :value="indicator.value"
                                    :key="indicator.value">
                                {{indicator.text}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 text-right">
                        <!-- Add Target Button -->
                        <button @click="editOutcomeIndicatorTarget()" class="btn btn-primary add-btn mt-2" type="button"><i
                            class="fa fa-plus"></i> Add Outcome Indicator
                        </button>
                        <!-- /Add Target Button -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- targets -->
                        <app-outcome-indicator-targets :targets="targets"/>
                        <!-- /targets -->
                        <!-- target form -->
                        <app-outcome-indicator-target-form/>
                        <!-- /target form -->
                    </div>
                </div>
            </div>
            <!-- /Outcome Indicator Targets Tab -->
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            keyResultAreaId: String,
            startOfNextFinancialYear: String,
        },
        created() {
            this.getKeyResultAreaDetails();
            EventBus.$on([
                'KEY_RESULT_AREA_SAVED',
                'OUTCOME_SAVED',
                'OUTCOME_INDICATOR_SAVED',
                'OUTCOME_INDICATOR_TARGET_SAVED',
            ], () => {
                this.$store.dispatch('GET_KEY_RESULT_AREA_DETAILS', this.keyResultAreaId);
            });
        },
        data() {
            return {
                outcomeId: '',
                outcomeIndicatorId: '',
                isLoading: false,
            }
        },
        watch:{
            outcomeId(newValue,oldValue){
                this.outcomeIndicatorId = '';
            }
        },
        computed: {
            ...mapGetters({
                keyResultArea: 'KEY_RESULT_AREA_DETAILS',
            }),
            outcomes() {
                return this.keyResultArea.outcomes || [];
            },
            outcomesOptions() {
                return this.outcomes.map((outcome) => {
                    return {
                        text: outcome.name,
                        value: outcome.id,
                    }
                });
            },
            indicators() {
                return this.keyResultArea.indicators.filter((indicator) => {
                    return indicator.outcomeId === this.outcomeId;
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
                return this.keyResultArea.targets.filter((target) => {
                    return target.outcomeIndicatorId === this.outcomeIndicatorId;
                });
            }
        },
        methods: {
            async getKeyResultAreaDetails() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_KEY_RESULT_AREA_DETAILS', this.keyResultAreaId);
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    await swal({title: error, icon: 'error'});
                    this.isLoading = false;
                }
            },
            editKeyResultArea() {
                EventBus.$emit("EDIT_KEY_RESULT_AREA", this.keyResultArea);
            },
            editOutcome(outcome = null) {
                EventBus.$emit("EDIT_OUTCOME", outcome);
            },

            editOutcomeIndicator(indicator = null) {
                EventBus.$emit("EDIT_OUTCOME_INDICATOR", indicator);
            },

            editOutcomeIndicatorTarget(target = null) {
                EventBus.$emit("EDIT_OUTCOME_INDICATOR_TARGET", target);
            },
        }
    }
</script>

<style scoped>

</style>
