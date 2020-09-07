<template>
    <div class="activity-output-indicators">
        <!--   Filters     -->
        <div class="row mb-2">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <label>Intervention</label>
                <select class="custom-select" v-model="interventionId">
                    <option value="">Select...</option>
                    <option v-for="intervention in interventionsOptions"
                            :value="intervention.value"
                            :key="intervention.value">{{intervention.text}}
                    </option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <label>Activity</label>
                <select class="custom-select" v-model="activityId">
                    <option value="">Select...</option>
                    <option v-for="activity in activitiesOptions"
                            :value="activity.value"
                            :key="activity.value">{{activity.text}}
                    </option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <label>Output</label>
                <select class="custom-select" v-model="outputId">
                    <option value="">Select...</option>
                    <option v-for="output in outputsOptions"
                            :value="output.value"
                            :key="output.value">{{output.text}}
                    </option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 text-right">
                <!-- Add Stage Button -->
                <div class="text-right mb-4 clearfix">
                    <button @click="editOutputIndicator()" class="btn btn-primary add-btn mt-2"
                            type="button"><i
                        class="fa fa-plus"></i> Add Indicator
                    </button>
                </div>
                <!-- /Add Stage Button -->
            </div>
        </div>
        <!--   /Filters     -->
        <!--   Indicators     -->
        <template v-if="isLoading">
            <Spinner/>
        </template>
        <template v-else>
            <OutputIndicators :indicators.sync="indicatorsForDisplay"/>
            <OutputIndicatorForm
                :interventions="interventions"
                :activities="activeWorkPlan.activities"
            />
        </template>
        <!--   /Indicators     -->
    </div>
</template>

<script>
    import OutputIndicators from "../indicators/OutputIndicators";
    import OutputIndicatorForm from "../indicators/OutputIndicatorForm";
    import {mapGetters} from "vuex";
    import Spinner from "../../shared/Spinner";
    import {EventBus} from "../../../app";

    export default {
        components: {OutputIndicatorForm, Spinner, OutputIndicators},
        created() {
            EventBus.$on(['OUTPUT_INDICATOR_SAVED'], this.getOutputIndicators);
        },
        data() {
            return {
                interventionId: '',
                activityId: '',
                outputId: '',
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                activeWorkPlan: "ACTIVE_WORK_PLAN",
                interventions: "INTERVENTIONS",
                outputs: "OUTPUTS",
                indicators: "OUTPUT_INDICATORS",
            }),
            interventionsOptions() {
                return this.interventions.map((intervention) => {
                    return {
                        text: intervention.name,
                        value: intervention.id,
                    }
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
            outputsOptions() {
                return this.outputs.map((output) => {
                    return {
                        value: output.id,
                        text: output.name,
                    }
                });
            },
            indicatorsForDisplay() {
                return this.indicators.filter((indicator) => {
                    return indicator.outputId === this.outputId;
                });
            },
        },
        watch: {
            interventionId(newValue, oldValue) {
                this.activityId = '';
                this.outputId = '';
            },
            activityId(newValue, oldValue) {
                this.outputId = '';
                this.getOutputs();
            },
            outputId(newValue, oldValue) {
                this.getOutputIndicators();
            },
        },
        methods: {
            editOutputIndicator(indicator = null) {
                EventBus.$emit("EDIT_OUTPUT_INDICATOR", {
                    indicator: indicator,
                    interventionId: this.interventionId,
                    activityId: this.activityId,
                    outputId: this.outputId,
                });
            },
            async getOutputs() {
                if (!!this.interventionId && !!this.activityId) {
                    await this.$store.dispatch("GET_OUTPUTS", {
                        interventionId: this.interventionId,
                        activityId: this.activityId,
                    });
                }
            },
            async getOutputIndicators() {
                if (!!this.outputId) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("GET_OUTPUT_INDICATORS", {
                            outputId: this.outputId,
                        });
                        this.isLoading = false;
                    } catch (error) {
                        console.error(error);
                        this.isLoading = false;
                    }
                }
            }
        },
    }
</script>

<style scoped>

</style>
