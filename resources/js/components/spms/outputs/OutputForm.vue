<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveOutput" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">Intervention <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="output.interventionId" :disabled="!!output.id" required>
                        <option value="">Select...</option>
                        <option v-for="intervention in interventionsOptions" :value="intervention.value"
                                :key="intervention.value">
                            {{intervention.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Name <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="output.name" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <textarea v-model="output.description" class="form-control" rows="5" required></textarea>
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
    import Output from "../../../models/smps/Output";
    import {mapGetters} from "vuex";

    export default {
        created() {
            EventBus.$on(["EDIT_OUTPUT"], this.editOutput);
        },
        data() {
            return {
                output: new Output(),
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
            title() {
                return (!!this.output.id) ? "Edit Output" : "Add Output";
            },
            formInvalid() {
                return this.isSending || !(!!this.output.name && !!this.output.description);
            },
        },
        methods: {
            editOutput(output = null) {
                if (output) {
                    this.output = deepClone(output);
                } else {
                    this.output = new Output();
                }
                this.isEditing = true;
            },
            async saveOutput() {
                try {
                    this.isSending = true;
                    if (!this.output.objectiveId) {
                        this.output.objectiveId = this.objective.id;
                    }
                    let response = await this.$store.dispatch('SAVE_OUTPUT', this.output);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OUTPUT_SAVED');
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
                this.output = new Output();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>
