<template>
    <MainModal :is-open="isEditing" :title="title" @modal-closed="resetForm()">
        <form @submit.prevent="saveDirAndResActivityOutput()" autocomplete="off">
            <template v-if="!!!dirAndResActivityOutput.id">
                <div class="form-group row">
                    <label class="col-md-4">Directive and Resolution</label>
                    <div class="col-md-8">
                        <select class="custom-select"
                                v-model="dirAndResActivityOutput.directiveAndResolutionId"
                                v-bind:class="{'is-invalid': !!!dirAndResActivityOutput.directiveAndResolutionId}">
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
                    <label class="col-md-4">Activity</label>
                    <div class="col-md-8">
                        <select class="custom-select" v-model="dirAndResActivityOutput.dirAndResActivityId"
                                v-bind:class="{'is-invalid': !!!dirAndResActivityOutput.dirAndResActivityId}">
                            <option value="">Select...</option>
                            <option v-for="activity in dirAndResActivities"
                                    :value="activity.id"
                                    :key="activity.id">
                                {{activity.title}}
                            </option>
                        </select>
                    </div>
                </div>
            </template>
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="dirAndResActivityOutput.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Target <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="dirAndResActivityOutput.target" type="number" class="form-control" min="0">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Unit <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <div class="row">
                        <label class="col-sm-6">
                            <input v-model="dirAndResActivityOutput.unit" type="radio" value="count"
                                   class="custom-radio">
                            Count
                        </label>
                        <label class="col-sm-6">
                            <input v-model="dirAndResActivityOutput.unit" type="radio" value="percent"
                                   class="custom-radio">
                            Percent
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Responsible Personnel</label>
                <div class="col-sm-8">
                    <select class="custom-select" v-model="dirAndResActivityOutput.responsiblePerson"
                            v-bind:class="{'is-invalid': !!!dirAndResActivityOutput.responsiblePerson}">
                        <option value="">Select...</option>
                        <option v-for="person in responsiblePersons"
                                :value="person.id"
                                :key="person.id">
                            {{person.fullName}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Description <span class="text-danger">*</span></label>
                <TinymceEditor
                    v-model="dirAndResActivityOutput.description"
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
    import DirAndResActivityOutput from "../../../models/smps/DirAndResActivityOutput";
    import {deepClone, displayErrorMessage} from "../../../utils/helpers";

    export default {
        components: {
            MainModal,
            TinymceEditor: require('@tinymce/tinymce-vue').default,
        },
        created() {
            EventBus.$on("EDIT_DIR_AND_RES_ACTIVITY_OUTPUT", this.editDirAndResActivityOutput);
        },

        data() {
            return {
                dirAndResActivityOutput: new DirAndResActivityOutput(),
                isEditing: false,
                isSending: false,
                responsiblePersons: [],
                dirAndResActivities: [],
            }
        },
        computed: {
            ...mapGetters({
                directivesAndResolutions: "DIRECTIVES_AND_RESOLUTIONS",
            }),
            title() {
                return !!this.dirAndResActivityOutput.id ? "Edit Activity Output" : "New Activity Output";
            },
            formInvalid() {
                return this.isSending || !(
                    !!this.dirAndResActivityOutput.title &&
                    !!this.dirAndResActivityOutput.description &&
                    !!this.dirAndResActivityOutput.directiveAndResolutionId &&
                    !!this.dirAndResActivityOutput.dirAndResActivityId &&
                    !!this.dirAndResActivityOutput.target &&
                    !!this.dirAndResActivityOutput.unit
                );
            }
        },
        watch: {
            "dirAndResActivityOutput.directiveAndResolutionId"(newVal, oldVal) {
                if(!!this.dirAndResActivityOutput.directiveAndResolutionId){
                    this.getDirectiveAndResolutionParams();
                }
            }
        },
        methods: {
            editDirAndResActivityOutput(output = null) {
                if (output) {
                    this.dirAndResActivityOutput = deepClone(output);
                    //this.$nextTick(this.getDirectiveAndResolutionParams);
                } else {
                    this.dirAndResActivityOutput = new DirAndResActivityOutput();
                }
                this.isEditing = true;
            },
            async getDirectiveAndResolutionParams() {
                let directiveAndResolution = this.directivesAndResolutions.find((dirAndRes) => dirAndRes.id === this.dirAndResActivityOutput.directiveAndResolutionId);
                if(!!directiveAndResolution){
                    try {
                        let response = await this.$store.dispatch('GET_DIRECTIVE_AND_RESOLUTION_PARAMS', {
                            directiveAndResolutionId: this.dirAndResActivityOutput.directiveAndResolutionId,
                            directorateId: directiveAndResolution.responsibilityCentreId
                        });
                        this.dirAndResActivities = response.activities;
                        this.responsiblePersons = response.employees;
                    } catch (error) {
                        await displayErrorMessage(error);
                    }
                }
            },
            async saveDirAndResActivityOutput() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DIR_AND_RES_ACTIVITY_OUTPUT', this.dirAndResActivityOutput);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('DIR_AND_RES_ACTIVITY_OUTPUT_SAVED');
                    this.resetForm();
                } catch (error) {
                    await displayErrorMessage(error);
                    this.isSending = false;
                }
            },
            resetForm() {
                this.dirAndResActivityOutput = new DirAndResActivityOutput();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
