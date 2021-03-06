<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveOutputIndicator" autocomplete="off">
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
                <label class="col-sm-4">Description</label>
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
        created() {
            EventBus.$on(["EDIT_OUTPUT_INDICATOR"], this.editOutputIndicator);
        },
        data() {
            return {
                indicator: new OutputIndicator(),
                isSending: false,
                isEditing: false,
            }
        },
        computed: {
            ...mapGetters({
                outputs: "OUTPUTS",
                activity: "ACTIVE_ACTIVITY",
                objective: "OBJECTIVE_DETAILS",
            }),
            title() {
                return (!!this.indicator.id) ? "Edit Output Indicator" : "Add Output Indicator";
            },
            outputsOptions() {
                return this.outputs.map((output) => {
                    return {
                        text: output.name,
                        value: output.id,
                    }
                });
            },
            formInvalid() {
                return this.isSending || !(!!this.indicator.outputId && !!this.indicator.name);
            },
        },
        methods: {
            editOutputIndicator(indicator = null) {
                if (indicator) {
                    this.indicator = deepClone(indicator);
                } else {
                    this.indicator = new OutputIndicator();
                }
                this.isEditing = true;
            },
            async saveOutputIndicator() {
                try {
                    this.isSending = true;
                    // we are doing this inside an an objective
                    if (!!this.objective && !this.indicator.objectiveId) {
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
