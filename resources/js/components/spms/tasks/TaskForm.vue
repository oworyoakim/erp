<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveTask" autocomplete="off">
            <div class="form-group row" v-if="!task.id">
                <label class="col-sm-4">Intervention <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="interventionId" :disabled="!!task.id" required>
                        <option value="">Select...</option>
                        <option v-for="intervention in interventionsOptions"
                                :value="intervention.value"
                                :key="intervention.value">
                            {{intervention.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" v-if="!task.id">
                <label class="col-sm-4">Activity <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="task.activityId" :disabled="!!task.id" required>
                        <option value="">Select...</option>
                        <option v-for="activity in activitiesOptions"
                                :value="activity.value"
                                :key="activity.value">
                            {{activity.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" v-if="!task.id">
                <label class="col-sm-4">Stage <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="task.stageId" :disabled="!!task.id" required>
                        <option value="">Select...</option>
                        <option v-for="stage in stagesOptions"
                                :value="stage.value"
                                :key="stage.value">
                            {{stage.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="task.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Due Date <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <app-date-range-picker
                        v-model="task.dueDate"
                        :value="task.dueDate"
                        :config="$store.getters.SINGLE_DATE_CONFIG"
                        :key="Math.random() * 10"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
<!--                    <textarea v-model="task.description" class="form-control" rows="5"></textarea>-->
                    <TinymceEditor
                        :api-key="$store.getters.TINYMCE_API_KEY"
                        :init="$store.getters.EDITOR_CONFIG"
                        v-model="task.description"
                    />
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
    import Task from "../../../models/smps/Task";
    import {mapGetters} from "vuex";
    import {deepClone} from "../../../utils/helpers";

    export default {
        components: {
            TinymceEditor: require('@tinymce/tinymce-vue').default,
        },
        created() {
            EventBus.$on(["EDIT_TASK"], this.editTask);
        },
        data() {
            return {
                task: new Task(),
                isEditing: false,
                isSending: false,
                interventionId: '',
            }
        },
        computed: {
            ...mapGetters({
                workPlan: "ACTIVE_WORK_PLAN",
                interventions: "INTERVENTIONS",
            }),
            interventionsOptions() {
                return this.interventions.map((intervention) => {
                    return {
                        text: intervention.name,
                        value: intervention.id,
                    }
                });
            },
            activitiesOptions() {
                if (!this.workPlan) {
                    return [];
                }
                return this.workPlan.activities.filter((activity) => {
                    return activity.interventionId === this.interventionId;
                }).map((activity) => {
                    return {
                        text: activity.title,
                        value: activity.id,
                    }
                });
            },
            stagesOptions() {
                if (!this.workPlan) {
                    return [];
                }
                return this.workPlan.stages.filter((stage) => {
                    return stage.activityId === this.task.activityId;
                }).map((stage) => {
                    return {
                        text: stage.title,
                        value: stage.id,
                    }
                });
            },
            title() {
                return (!!this.task.id) ? "Edit Task" : "Add Task";
            },
            formInvalid() {
                return this.isSending || !(!!this.task.activityId && !!this.task.stageId && !!this.task.title && !!this.task.dueDate);
            },
            selectedStage() {
                if (!this.workPlan) {
                    return null;
                }
                return this.workPlan.stages.find((stage) => stage.id === this.task.stageId) || null;
            },
        },
        watch: {
            selectedStage(newVal, oldVal) {
                if (!!this.selectedStage) {
                    this.dateConfig.minDate = this.selectedStage.startDate;
                    this.dateConfig.maxDate = this.selectedStage.dueDate;
                }
            }
        },
        methods: {
            editTask(task = null) {
                if (task) {
                    this.task = deepClone(task);
                } else {
                    this.task = new Task();
                }
                this.isEditing = true;
            },
            async saveTask() {
                try {
                    this.isSending = true;
                    if (!this.task.workPlanId) {
                        this.task.workPlanId = this.workPlan.id;
                    }
                    let response = await this.$store.dispatch('SAVE_TASK', this.task);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('TASK_SAVED');
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
                this.task = new Task();
                this.interventionId = '';
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
