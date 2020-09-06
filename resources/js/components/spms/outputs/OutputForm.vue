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
            <div class="form-group row" v-if="!!activity">
                <label class="col-sm-4">Activity <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="output.activityId" :disabled="!!output.id" required>
                        <option value="">Select...</option>
                        <option v-for="activity in activitiesOptions" :value="activity.value"
                                :key="activity.value">
                            {{activity.text}}
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
        props: {
            activity: {type: Boolean, default: false,},
        },
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
                activities: "ACTIVITIES",
                interventions: "INTERVENTIONS",
                workPlan: "ACTIVE_WORK_PLAN",
            }),
            interventionsOptions() {
                if (!!this.objective) {
                    return this.objective.interventions.map((intervention) => {
                        return {
                            text: intervention.name,
                            value: intervention.id,
                        }
                    });
                }
                return this.interventions.map((intervention) => {
                    return {
                        text: intervention.name,
                        value: intervention.id,
                    }
                });
            },
            activitiesOptions() {
                if (!!this.activity && !!this.workPlan) {
                    return this.workPlan.activities.filter((activity) => {
                        return activity.interventionId === this.output.interventionId;
                    }).map((activity) => {
                        return {
                            text: activity.title,
                            value: activity.id,
                        }
                    });
                }
                return [];
            },
            title() {
                return (!!this.output.id) ? "Edit Output" : "Add Output";
            },
            formInvalid() {
                return this.isSending || !(
                    !!this.output.name &&
                    !!this.output.description &&
                    (!this.activity || !!this.output.activityId)
                );
            },
        },
        methods: {
            editOutput(data) {
                if (data.output) {
                    this.output = deepClone(data.output);
                } else {
                    this.output = new Output();
                    this.output.interventionId = data.interventionId;
                    this.output.activityId = data.activityId;
                }
                this.isEditing = true;
            },
            async saveOutput() {
                try {
                    this.isSending = true;
                    if (!this.output.objectiveId) {
                        if (!!this.objective) {
                            this.output.objectiveId = this.objective.id;
                        } else {
                            let intervention = this.interventions.find((intervention) => intervention.id === this.output.interventionId);
                            if (!intervention) {
                                return await swal({title: 'Strategic objective not set!', icon: 'error'});
                            }
                            this.output.objectiveId = intervention.objectiveId;
                        }
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
