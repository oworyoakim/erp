<template>
    <MainModal size="xl" :is-open="isEditing" :title="title" @modal-closed="resetForm()">
        <form @submit.prevent="completeDirAndResActivity()" autocomplete="off">
            <table class="table">
                <thead>
                <tr>
                    <th>Output</th>
                    <th>Target</th>
                    <th>Actual</th>
                    <th>Comment</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="output in dirAndResActivity.outputs">
                    <td class="width-50">{{output.title}}</td>
                    <td class="width-15">
                        {{output.target}}
                        <template v-if="output.unit === 'percent'">%</template>
                    </td>
                    <td class="width-15">
                        <input type="number" v-model="output.actual" class="form-control form-control-sm">
                    </td>
                    <td class="width-20">
                        <textarea v-model="output.comment" class="form-control form-control-sm" rows="3"></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
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
    import DirAndResActivityOutput from "../../../models/smps/DirAndResActivityOutput";
    import {deepClone, displayErrorMessage} from "../../../utils/helpers";
    import DirAndResActivity from "../../../models/smps/DirAndResActivity";

    export default {
        components: {
            MainModal,
        },
        created() {
            EventBus.$on("COMPLETE_ACTIVITY", this.setActivityOutputsActuals);
        },

        data() {
            return {
                dirAndResActivity: new DirAndResActivity(),
                isSending: false,
                oldOutputs: null,
            }
        },
        computed: {
            isEditing() {
                return !!this.dirAndResActivity && !!this.dirAndResActivity.id && this.dirAndResActivity.outputs.length > 0;
            },
            title() {
                return "Activity Completion Form";
            },
            formInvalid() {
                let unfilledActuals = this.dirAndResActivity.outputs.some((output) => !output.actual);
                return this.isSending || this.$isEqual(this.dirAndResActivity.outputs, this.oldOutputs) || unfilledActuals;
            }
        },
        methods: {
            setActivityOutputsActuals(activity) {
                this.dirAndResActivity = activity;
                this.oldOutputs = deepClone(activity.outputs);
            },
            async completeDirAndResActivity() {
                try {
                    let warning = "You will mark this activity as completed!";
                    let isConfirmed = await swal({
                        title: 'Are you sure?',
                        text: warning,
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Yes'
                        ],
                        closeOnClickOutside: false
                    });
                    if (isConfirmed) {
                        let {id, directiveAndResolutionId, outputs} = this.dirAndResActivity;
                        let activity = {id, directiveAndResolutionId, outputs};
                        // console.log(activity);
                        this.isSending = true;
                        let response = await this.$store.dispatch('COMPLETE_DIR_AND_RES_ACTIVITY', activity);
                        toastr.success(response);
                        this.isSending = false;
                        EventBus.$emit('DIR_AND_RES_ACTIVITY_OUTPUT_SAVED');
                        this.resetForm();
                    }
                } catch (error) {
                    await displayErrorMessage(error);
                    this.isSending = false;
                }
            },
            resetForm() {
                this.dirAndResActivity = new DirAndResActivity();
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>
    .width-15 {
        width: 15% !important;
    }

    .width-20 {
        width: 20% !important;
    }

    .width-50 {
        width: 50% !important;
    }
</style>
