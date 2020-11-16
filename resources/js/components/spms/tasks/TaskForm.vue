<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveTask" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">Stage <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="task.stageId" required>
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
                    <DateRangePicker
                        v-model="task.dueDate"
                        :value="task.dueDate"
                        :config="$store.getters.SINGLE_DATE_CONFIG"
                        :key="Math.random() * 10"
                    />
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label>Description</label>
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
    import DateRangePicker from "../../shared/DateRangePicker";

    export default {
        components: {
            DateRangePicker,
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
            }
        },
        computed: {
            ...mapGetters({
                workPlan: "ACTIVE_WORK_PLAN",
                mainActivity: "ACTIVE_MAIN_ACTIVITY",
                activity: "ACTIVE_ACTIVITY",
            }),
            stagesOptions() {
                if (!this.activity) {
                    return [];
                }
                return this.activity.stages.map((stage) => {
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
                return this.isSending || !(!!this.task.stageId && !!this.task.title && !!this.task.dueDate);
            },
            selectedStage() {
                if (!this.activity) {
                    return null;
                }
                let stage = this.activity.stages.find((stage) => stage.id === this.task.stageId);
                return stage  || null;
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
                    if (!this.task.activityId) {
                        this.task.activityId = this.activity.id;
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
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
