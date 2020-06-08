<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveOutcomeIndicatorTarget" autocomplete="off">
            <div class="form-group row" v-if="!!!target.id">
                <label class="col-sm-4">Outcome <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="outcomeId" :disabled="!!target.id">
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
            <div class="form-group row" v-if="!!!target.id">
                <label class="col-sm-4">Indicator <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="target.outcomeIndicatorId" :disabled="!!target.id">
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
                <label class="col-sm-4">Target Value <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="target.target" class="form-control" type="number" min="1" required>
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
    import OutcomeIndicatorTarget from "../../../models/smps/OutcomeIndicatorTarget";

    export default {
        created() {
            EventBus.$on(["EDIT_OUTCOME_INDICATOR_TARGET"], this.editOutcomeIndicatorTarget);
        },
        data() {
            return {
                target: new OutcomeIndicatorTarget(),
                outcomeId: '',
                outcomeIndicatorId: '',
                isEditing: false,
                isSending: false,
            }
        },
        computed: {
            ...mapGetters({
                keyResultArea: "KEY_RESULT_AREA_DETAILS",
            }),
            outcomesOptions() {
                return this.keyResultArea.outcomes.map((outcome) => {
                    return {
                        text: outcome.name,
                        value: outcome.id,
                    }
                });
            },
            indicatorsOptions() {
                return this.keyResultArea.indicators.filter((indicator) => {
                    return indicator.outcomeId === this.outcomeId;
                }).map((outcome) => {
                    return {
                        text: outcome.name,
                        value: outcome.id,
                    }
                });
            },
            reportPeriodsOptions() {
                return this.keyResultArea.reportPeriods.map((period) => {
                    return {
                        text: period.name,
                        value: period.id,
                    }
                });
            },
            title() {
                return (!!this.target.id) ? "Edit Outcome Indicator Target" : "Add Outcome Indicator Target";
            },
            formInvalid() {
                return this.isSending || !(!!this.target.target && !!this.target.outcomeIndicatorId && !!this.target.reportPeriodId);
            },
        },
        methods: {
            editOutcomeIndicatorTarget(target = null) {
                if (target) {
                    this.target = deepClone(target);
                } else {
                    this.target = new OutcomeIndicatorTarget();
                }
                this.isEditing = true;
            },
            async saveOutcomeIndicatorTarget() {
                try {
                    this.isSending = true;
                    if (!this.target.keyResultAreaId) {
                        this.target.keyResultAreaId = this.keyResultArea.id;
                    }
                    let response = await this.$store.dispatch('SAVE_OUTCOME_INDICATOR_TARGET', this.target);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OUTCOME_INDICATOR_TARGET_SAVED');
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
                this.indicator = new OutcomeIndicatorTarget();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>
