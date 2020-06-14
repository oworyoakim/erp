<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveOutputIndicatorTarget" autocomplete="off">
            <div class="form-group row" v-if="!!!target.id">
                <label class="col-sm-4">Intervention <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="interventionId" :disabled="!!target.id">
                        <option value="">Select...</option>
                        <option v-for="intervention in interventionsOptions"
                                :value="intervention.value"
                                :key="intervention.value"
                        >
                            {{intervention.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" v-if="!!!target.id">
                <label class="col-sm-4">Output <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="outputId" :disabled="!!target.id">
                        <option value="">Select...</option>
                        <option v-for="output in outputsOptions"
                                :value="output.value"
                                :key="output.value"
                        >
                            {{output.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" v-if="!!!target.id">
                <label class="col-sm-4">Indicator <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="target.outputIndicatorId" :disabled="!!target.id">
                        <option value="">Select...</option>
                        <option v-for="indicator in indicatorsOptions"
                                :value="indicator.value"
                                :key="indicator.value"
                        >
                            {{indicator.text}}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group row" v-if="!!!target.id">
                <label class="col-sm-4">Report Period <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="target.reportPeriodId" required>
                        <option value="">Select...</option>
                        <option v-for="period in reportPeriodsOptions"
                                :value="period.value"
                                :key="period.value"
                        >
                            {{period.text}}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4">Target Value<span v-if="!!indicatorUnitText"> ({{indicatorUnitText}})</span> <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="target.target" class="form-control" type="number" min="0" :max="maxTargetValue" required>
                </div>
            </div>

            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>
<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";
    import OutputIndicatorTarget from "../../../models/smps/OutputIndicatorTarget";

    export default {
        created() {
            EventBus.$on(["EDIT_OUTPUT_INDICATOR_TARGET"], this.editOutputIndicatorTarget);
        },
        data() {
            return {
                target: new OutputIndicatorTarget(),
                interventionId: '',
                outputId: '',
                outputIndicatorId: '',
                isEditing: false,
                isSending: false,
            }
        },
        computed: {
            ...mapGetters({
                objective: "OBJECTIVE_DETAILS",
            }),
            interventionsOptions() {
                return this.objective.interventions.map((intervention) => {
                    return {
                        text: intervention.name,
                        value: intervention.id,
                    }
                });
            },
            outputsOptions() {
                return this.objective.outputs.filter((output) => {
                    return output.interventionId === this.interventionId;
                }).map((output) => {
                    return {
                        text: output.name,
                        value: output.id,
                    }
                });
            },
            indicatorsOptions() {
                return this.objective.indicators.filter((indicator) => {
                    return indicator.outputId === this.outputId;
                }).map((output) => {
                    return {
                        text: output.name,
                        value: output.id,
                    }
                });
            },
            reportPeriodsOptions() {
                return this.objective.reportPeriods.map((period) => {
                    return {
                        text: period.name,
                        value: period.id,
                    }
                });
            },
            indicatorUnit(){
                if(!this.target.outputIndicatorId){
                    return null;
                }
                let outputIndicator = this.objective.indicators.find((indicator) => indicator.id === this.target.outputIndicatorId);
                if(!!outputIndicator){
                    return outputIndicator.unit;
                }
                return null;
            },
            indicatorUnitText(){
                if(!this.indicatorUnit){
                    return null;
                }
               return (this.indicatorUnit === 'percent') ? "%age" : "count";
            },
            maxTargetValue(){
                return (this.indicatorUnit && this.indicatorUnit === 'percent') ? 100 : null;
            },
            title() {
                return (!!this.target.id) ? "Edit Output Indicator Target" : "Add Output Indicator Target";
            },
            formInvalid() {
                return this.isSending || !(!!this.target.target && !!this.target.outputIndicatorId && !!this.target.reportPeriodId);
            },
        },
        methods: {
            editOutputIndicatorTarget(target = null) {
                if (target) {
                    this.target = deepClone(target);
                } else {
                    this.target = new OutputIndicatorTarget();
                }
                this.isEditing = true;
            },
            async saveOutputIndicatorTarget() {
                try {
                    this.isSending = true;
                    if (!this.target.objectiveId) {
                        this.target.objectiveId = this.objective.id;
                    }
                    let response = await this.$store.dispatch('SAVE_OUTPUT_INDICATOR_TARGET', this.target);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OUTPUT_INDICATOR_TARGET_SAVED');
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.indicator = new OutputIndicatorTarget();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>
