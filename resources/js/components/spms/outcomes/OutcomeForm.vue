<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveOutcome" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">Name <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="outcome.name" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <textarea v-model="outcome.description" class="form-control" rows="5" required></textarea>
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
    import Outcome from "../../../models/smps/Outcome";
    import {mapGetters} from "vuex";

    export default {
        created() {
            EventBus.$on(["EDIT_OUTCOME"], this.editOutcome);
        },
        data() {
            return {
                outcome: new Outcome(),
                isEditing: false,
                isSending: false,
            }
        },
        computed: {
            ...mapGetters({
                keyResultArea: "KEY_RESULT_AREA_DETAILS",
            }),
            title() {
                return (!!this.outcome.id) ? "Edit Outcome" : "Add Outcome";
            },
            formInvalid() {
                return this.isSending || !(!!this.outcome.name && !!this.outcome.description);
            },
        },
        methods: {
            editOutcome(outcome = null) {
                if (outcome) {
                    this.outcome = deepClone(outcome);
                } else {
                    this.outcome = new Outcome();
                }
                this.isEditing = true;
            },
            async saveOutcome() {
                try {
                    this.isSending = true;
                    if (!this.outcome.keyResultAreaId) {
                        this.outcome.keyResultAreaId = this.keyResultArea.id;
                    }
                    let response = await this.$store.dispatch('SAVE_OUTCOME', this.outcome);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OUTCOME_SAVED');
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
                this.outcome = new Outcome();
                this.isEditing = false;
            }
        }
    }
</script>
