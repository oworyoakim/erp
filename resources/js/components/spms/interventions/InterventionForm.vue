<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveIntervention" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">Name <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" v-model="intervention.name" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Rank <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select v-model="intervention.rank" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="i in 10" :key="i" :value="i">{{i}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <textarea v-model="intervention.description" class="form-control" rows="5"></textarea>
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
    import Intervention from "../../../models/smps/Intervention";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        created() {
            EventBus.$on(["EDIT_INTERVENTION"], this.editIntervention);
        },
        data() {
            return {
                intervention: new Intervention(),
                isEditing: false,
                isSending: false,
            }
        },
        computed: {
            ...mapGetters({
                objective: "OBJECTIVE_DETAILS"
            }),
            title() {
                return (!!this.intervention.id) ? "Edit Intervention" : "Add Intervention";
            },
            formInvalid() {
                return this.isSending || !(!!this.intervention.name && !!this.intervention.rank && !!this.intervention.description);
            },
        },
        methods: {
            editIntervention(intervention = null) {
                if (intervention) {
                    this.intervention = deepClone(intervention);
                } else {
                    this.intervention = new Intervention();
                }
                this.isEditing = true;
            },
            async saveIntervention() {
                try {
                    this.isSending = true;
                    if (!this.intervention.objectiveId) {
                        this.intervention.objectiveId = this.objective.id;
                    }
                    let response = await this.$store.dispatch('SAVE_INTERVENTION', this.intervention);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('INTERVENTION_SAVED');
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.intervention = new Intervention();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>
