<template>
    <div id="outputIndicatorModal" ref="outputIndicatorModal" class="modal custom-modal fade" role="dialog"
         tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span v-if="!!indicator.id">Edit Output Indicator</span>
                        <span v-else>Add Output Indicator</span>
                    </h5>
                    <button type="button" class="close" @click="closePreview()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveOutputIndicator" autocomplete="off">
                        <div class="form-group row" v-if="!!!indicator.id">
                            <label class="col-sm-4">Intervention <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="interventionId" :disabled="!!indicator.id">
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
                            <label class="col-sm-4">Output <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="indicator.outputId" :disabled="!!indicator.id">
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
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";
    import OutputIndicator from "../../../models/smps/OutputIndicator";
    import {mapGetters} from "vuex";

    export default {
        created() {
            EventBus.$on(["EDIT_OUTPUT_INDICATOR"], this.editOutputIndicator);
        },
        data() {
            return {
                indicator: new OutputIndicator(),
                interventionId: '',
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
            formInvalid() {
                return this.isSending || !(!!this.indicator.outputId && !!this.indicator.name && !!this.indicator.description);
            },
        },
        methods: {
            editOutputIndicator(indicator = null) {
                if (indicator) {
                    this.indicator = deepClone(indicator);
                } else {
                    this.indicator = new OutputIndicator();
                }
                $(this.$refs.outputIndicatorModal).modal("show");
            },
            async saveOutputIndicator() {
                try {
                    this.isSending = true;
                    if (!this.indicator.objectiveId) {
                        this.indicator.objectiveId = this.objective.id;
                    }
                    let response = await this.$store.dispatch('SAVE_OUTPUT_INDICATOR', this.indicator);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OUTPUT_INDICATOR_SAVED');
                    this.closePreview();
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.indicator = new OutputIndicator();
                $(this.$refs.outputIndicatorModal).modal('hide');
                $(".modal-backdrop").remove();
            }
        }
    }
</script>
