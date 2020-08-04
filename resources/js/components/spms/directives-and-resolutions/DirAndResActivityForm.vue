<template>
    <MainModal :is-open="isEditing" :title="title" @modal-closed="resetForm()">
        <form @submit.prevent="saveDirAndResActivity()" autocomplete="off">
            <div class="form-group row">
                <label class="col-md-4">Directive and Resolution</label>
                <div class="col-md-8">
                    <select class="custom-select"
                            v-model="dirAndResActivity.directiveAndResolutionId"
                            v-bind:class="{'is-invalid': !!!dirAndResActivity.directiveAndResolutionId}">
                        <option>Select...</option>
                        <option v-for="dirAndRes in directivesAndResolutions"
                                :value="dirAndRes.id"
                                :key="dirAndRes.id">
                            {{dirAndRes.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="dirAndResActivity.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Start Date</label>
                <div class="col-sm-8">
                    <DateRangePicker
                        v-model="dirAndResActivity.startDate"
                        :config="{...$store.getters.SINGLE_DATE_CONFIG, maxDate: maxDueDate}"
                        :key="Math.random()"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Due Date <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <DateRangePicker
                        v-model="dirAndResActivity.dueDate"
                        :config="{...$store.getters.SINGLE_DATE_CONFIG, maxDate: maxDueDate}"
                        :key="Math.random()"
                    />
                </div>
            </div>
            <div class="form-group">
                <label>Description <span class="text-danger">*</span></label>
                <TinymceEditor
                    v-model="dirAndResActivity.description"
                    :api-key="$store.getters.TINYMCE_API_KEY"
                    :init="$store.getters.EDITOR_CONFIG"
                />
            </div>
            <div class="submit-section">
                <button type="submit" :disabled="formInvalid" class="btn btn-primary submit-btn btn-block">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </MainModal>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import MainModal from "../../shared/MainModal";
    import DirAndResActivity from "../../../models/smps/DirAndResActivity";
    import DateRangePicker from "../../shared/DateRangePicker";
    import {deepClone, displayErrorMessage} from "../../../utils/helpers";

    export default {
        components: {
            MainModal,
            DateRangePicker,
            TinymceEditor: require('@tinymce/tinymce-vue').default,
        },
        created() {
            EventBus.$on("EDIT_DIR_AND_RES_ACTIVITY", this.editDirAndResActivity);
        },
        data() {
            return {
                dirAndResActivity: new DirAndResActivity(),
                isEditing: false,
                isSending: false,
            }
        },
        computed: {
            ...mapGetters({
                workPlan: "ACTIVE_WORK_PLAN",
                directivesAndResolutions: "DIRECTIVES_AND_RESOLUTIONS",
            }),
            maxDueDate(){
                let dirAndRes = this.directivesAndResolutions.find((dirAndRes) => dirAndRes.id === this.dirAndResActivity.directiveAndResolutionId);
                return (!!dirAndRes) ? dirAndRes.deadline : null;
            },
            title() {
                return !!this.dirAndResActivity.id ? "Edit Activity" : "New Activity";
            },
            formInvalid() {
                return this.isSending || !(
                    !!this.dirAndResActivity.title &&
                    !!this.dirAndResActivity.dueDate &&
                    !!this.dirAndResActivity.directiveAndResolutionId
                );
            }
        },
        methods: {
            editDirAndResActivity(activity = null) {
                if (activity) {
                    this.dirAndResActivity = deepClone(activity);
                } else {
                    this.dirAndResActivity = new DirAndResActivity();
                }
                this.isEditing = true;
            },
            async saveDirAndResActivity() {
                try {
                    if (!this.dirAndResActivity.workPlanId) {
                        this.dirAndResActivity.workPlanId = this.workPlan.id;
                    }
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DIR_AND_RES_ACTIVITY', this.dirAndResActivity);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('DIR_AND_RES_ACTIVITY_SAVED');
                    this.resetForm();
                } catch (error) {
                    await displayErrorMessage(error);
                    this.isSending = false;
                }
            },
            resetForm() {
                this.dirAndResActivity = new DirAndResActivity();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
