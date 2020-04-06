<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveOutcomeIndicator" autocomplete="off">
            <div class="form-group row" v-if="!!!indicator.id">
                <label class="col-sm-4">Outcome <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="indicator.outcomeId" :disabled="!!indicator.id">
                        <option value="">Select...</option>
                        <option v-for="outcome in outcomesOptions"
                                :value="outcome.value"
                                :key="outcome.value"
                        >
                            {{outcome.text}}
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
                <label class="col-sm-4">Baseline <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="indicator.baseline" class="form-control" type="number" min="0" required>
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
    import OutcomeIndicator from "../../../models/smps/OutcomeIndicator";
    import {mapGetters} from "vuex";

    export default {
        created() {
            EventBus.$on(["EDIT_OUTCOME_INDICATOR"], this.editOutcomeIndicator);
        },
        data() {
            return {
                indicator: new OutcomeIndicator(),
                isSending: false,
                isEditing: false,
            }
        },
        computed: {
            ...mapGetters({
                keyResultArea: "KEY_RESULT_AREA_DETAILS",
            }),
            title(){
                return (!!this.indicator.id) ? "Edit Outcome Indicator" : "Add Outcome Indicator";
            },
            outcomesOptions() {
                return this.keyResultArea.outcomes.map((outcome) => {
                    return {
                        text: outcome.name,
                        value: outcome.id,
                    }
                });
            },
            formInvalid() {
                return this.isSending || !(!!this.indicator.outcomeId && !!this.indicator.name && !!this.indicator.description);
            },
        },
        methods: {
            editOutcomeIndicator(indicator = null) {
                if (indicator) {
                    this.indicator = deepClone(indicator);
                } else {
                    this.indicator = new OutcomeIndicator();
                }
                this.isEditing = true;
            },
            async saveOutcomeIndicator() {
                try {
                    this.isSending = true;
                    if (!this.indicator.keyResultAreaId) {
                        this.indicator.keyResultAreaId = this.keyResultArea.id;
                    }
                    let response = await this.$store.dispatch('SAVE_OUTCOME_INDICATOR', this.indicator);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OUTCOME_INDICATOR_SAVED');
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
                this.indicator = new OutcomeIndicator();
                this.isEditing = false;
            }
        }
    }
</script>
