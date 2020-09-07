<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveOutputIndicator" autocomplete="off">
            <div class="form-group row" v-if="!!!indicator.id">
                <label class="col-sm-4">Intervention <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="interventionId" :disabled="!!indicator.id" required>
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
            <div class="form-group row" v-if="!!!indicator.id">
                <label class="col-sm-4">Activity</label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="activityId" :disabled="!!indicator.id">
                        <option value="">Select...</option>
                        <option v-for="activity in activitiesOptions"
                                :value="activity.value"
                                :key="activity.value"
                        >
                            {{activity.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" v-if="!!!indicator.id">
                <label class="col-sm-4">Output <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="indicator.outputId" :disabled="!!indicator.id" required>
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
            <div class="form-group row">
                <label class="col-sm-4">Name <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="indicator.name" class="form-control" type="text" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4">Measured As <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="indicator.unit" required>
                        <option value="">Select...</option>
                        <option value="count">Count</option>
                        <option value="percent">Percentage (%)</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <textarea v-model="indicator.description" class="form-control" rows="5"></textarea>
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
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";
    import OutputIndicator from "../../../models/smps/OutputIndicator";
    import {mapGetters} from "vuex";

    export default {
        props: {
            interventions: {type: Array, default: () => []},
            activities: {type: Array, default: () => []},
        },
        created() {
            EventBus.$on(["EDIT_OUTPUT_INDICATOR"], this.editOutputIndicator);
        },
        data() {
            return {
                indicator: new OutputIndicator(),
                interventionId: '',
                activityId: '',
                isSending: false,
                isEditing: false,
            }
        },
        computed: {
            ...mapGetters({
                outputs: "OUTPUTS",
            }),
            objectiveId() {
                if (!this.interventionId) {
                    return null;
                }
                let intervention = this.interventions.find((intervention) => intervention.id === this.interventionId);
                return (!!intervention) ? intervention.objectiveId : null;
            },
            title() {
                return (!!this.indicator.id) ? "Edit Output Indicator" : "Add Output Indicator";
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
                return this.activities.filter((activity) => {
                    return activity.interventionId === this.interventionId;
                }).map((activity) => {
                    return {
                        text: activity.title,
                        value: activity.id,
                    }
                });
            },
            outputsOptions() {
                return this.outputs.filter((output) => {
                    if (!!this.activityId) {
                        return output.activityId === this.activityId;
                    }
                    return output.interventionId === this.interventionId;
                }).map((output) => {
                    return {
                        text: output.name,
                        value: output.id,
                    }
                });
            },
            formInvalid() {
                return this.isSending || !(!!this.indicator.outputId && !!this.indicator.name && !!this.indicator.description);
            },
        },
        watch: {
            interventionId(newValue, oldValue) {
                this.indicator.outputId = '';
                if(!!this.interventionId){
                    this.$store.dispatch("GET_OUTPUTS",{
                        interventionId: this.interventionId,
                    });
                }
            },
            activityId(newValue, oldValue) {
                this.indicator.outputId = '';
            },
        },
        methods: {
            editOutputIndicator(data = {}) {
                if (data.indicator) {
                    this.indicator = deepClone(data.indicator);
                } else {
                    this.indicator = new OutputIndicator();
                }
                if(!!data.interventionId){
                    this.interventionId =  data.interventionId;
                }
                if(!!data.activityId){
                    this.activityId =  data.activityId;
                }
                if(!!data.outputId){
                    this.indicator.outputId =  data.outputId;
                }
                this.isEditing = true;
            },
            async saveOutputIndicator() {
                try {
                    this.isSending = true;
                    if (!this.indicator.objectiveId) {
                        this.indicator.objectiveId = this.objectiveId;
                    }
                    let response = await this.$store.dispatch('SAVE_OUTPUT_INDICATOR', this.indicator);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OUTPUT_INDICATOR_SAVED');
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
                this.indicator = new OutputIndicator();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>
