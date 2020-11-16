<template>
    <div class="activity-output-indicators">
        <template v-if="isLoading">
            <Spinner/>
        </template>
        <template v-else>
            <!--   Filters     -->
            <div class="row mb-2">
                <div class="col-lg-9 col-md-8 col-sm-6">
                    <label>Output</label>
                    <select class="custom-select" v-model="outputId">
                        <option value="">Select...</option>
                        <option v-for="output in outputsOptions"
                                :value="output.value"
                                :key="output.value">{{ output.text }}
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
            <OutputIndicators :indicators="indicatorsForDisplay"/>
            <!--   /Indicators     -->
            <OutputIndicatorForm/>
        </template>
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
        this.$parent.$on('loadIndicators', () => {
            this.getOutputIndicators().then(() => {
                this.$store.dispatch('GET_OUTPUTS', {
                    activityId: this.activity.id
                });
            });
        });
    },
    data() {
        return {
            outputId: '',
            isLoading: false,
        }
    },
    computed: {
        ...mapGetters({
            activeWorkPlan: "ACTIVE_WORK_PLAN",
            outputs: "OUTPUTS",
            indicators: "OUTPUT_INDICATORS",
            mainActivity: "ACTIVE_MAIN_ACTIVITY",
            activity: "ACTIVE_ACTIVITY",
        }),
        outputsOptions() {
            return this.outputs.map((output) => {
                return {
                    value: output.id,
                    text: output.name,
                }
            });
        },
        indicatorsForDisplay() {
            if (!!this.outputId) {
                return this.indicators.filter((indicator) => {
                    return indicator.outputId === this.outputId;
                });
            }
            return this.indicators;
        },
    },
    methods: {
        editOutputIndicator(indicator = null) {
            EventBus.$emit("EDIT_OUTPUT_INDICATOR", indicator);
        },
        async getOutputs() {
            try{
                await this.$store.dispatch("GET_OUTPUTS", {
                    activityId: this.activity.id,
                });
            }catch (error){
                console.log(error);
            }
        },
        async getOutputIndicators() {
            try {
                this.isLoading = true;
                await this.$store.dispatch("GET_OUTPUT_INDICATORS", {
                    activityId: this.activity.id,
                });
                this.isLoading = false;
            } catch (error) {
                console.error(error);
                this.isLoading = false;
            }
        }
    },
}
</script>

<style scoped>

</style>
