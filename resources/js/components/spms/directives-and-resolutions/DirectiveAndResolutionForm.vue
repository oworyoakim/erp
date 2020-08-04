<template>
    <MainModal :is-open="isEditing" :title="title" @modal-closed="resetForm()">
        <form @submit.prevent="saveDirectiveAndResolution()" autocomplete="off">
            <fieldset v-if="step === 1">
                <h2 class="mb-2 border-bottom">Step 1 of 3</h2>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Type <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <div class="form-check form-check-inline">
                            <input type="radio" v-model="directiveAndResolution.type" class="form-check-input"
                                   id="type_directive" value="directive">
                            <label class="form-check-label" for="type_directive">Directive</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" v-model="directiveAndResolution.type" class="form-check-input"
                                   id="type_resolution" value="resolution">
                            <label class="form-check-label" for="type_resolution">Resolution</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input v-model="directiveAndResolution.title" class="form-control" type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description <span class="text-danger">*</span></label>
                    <TinymceEditor
                        v-model="directiveAndResolution.description"
                        :api-key="$store.getters.TINYMCE_API_KEY"
                        :init="$store.getters.EDITOR_CONFIG"
                    />
                </div>
                <div class="submit-section">
                    <button :disabled="isSending" @click.prevent="next()"
                            class="btn btn-outline-info submit-btn pull-right"><i
                        class="fa fa-arrow-right "></i>
                        Next
                    </button>
                </div>
            </fieldset>
            <fieldset v-if="step === 2">
                <h2 class="mb-2 border-bottom">Step 2 of 3</h2>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Source Type <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <div class="form-check form-check-inline">
                            <input type="radio" v-model="directiveAndResolution.sourceType" class="form-check-input"
                                   id="source_type_internal" value="internal">
                            <label class="form-check-label" for="source_type_internal">Internal</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" v-model="directiveAndResolution.sourceType" class="form-check-input"
                                   id="source_type_external" value="external">
                            <label class="form-check-label" for="source_type_external">External</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Organisation</label>
                    <div class="col-sm-8">
                        <input v-model="directiveAndResolution.sourceOrganization" class="form-control" type="text">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Office</label>
                    <div class="col-sm-8">
                        <input v-model="directiveAndResolution.sourceOffice" class="form-control" type="text">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Officer</label>
                    <div class="col-sm-8">
                        <input v-model="directiveAndResolution.sourceOfficer" class="form-control" type="text">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Telephone</label>
                    <div class="col-sm-8">
                        <input v-model="directiveAndResolution.sourceTelephone" class="form-control" type="text">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Email</label>
                    <div class="col-sm-8">
                        <input v-model="directiveAndResolution.sourceEmail" class="form-control" type="email">
                    </div>
                </div>
                <div class="submit-section">
                    <button :disabled="isSending" @click.prevent="prev()"
                            class="btn btn-outline-info submit-btn pull-left"><i
                        class="fa fa-arrow-left "></i>
                        Previous
                    </button>
                    <button :disabled="isSending" @click.prevent="next()"
                            class="btn btn-outline-info submit-btn pull-right"><i
                        class="fa fa-arrow-right "></i>
                        Next
                    </button>
                </div>
            </fieldset>
            <fieldset v-if="step === 3">
                <h2 class="mb-2 border-bottom">Step 3 of 3</h2>
                <div class="form-group row">
                    <label class="col-sm-4">Responsibility Center <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select v-model="directiveAndResolution.responsibilityCentreId" class="form-control">
                            <option>Select...</option>
                            <option v-for="directorate in directorates" :value="directorate.id">
                                {{directorate.title}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Date Received <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <app-date-range-picker
                            v-model="directiveAndResolution.dateReceived"
                            :config="$store.getters.SINGLE_DATE_CONFIG"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Deadline <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <app-date-range-picker
                            v-model="directiveAndResolution.deadline"
                            :config="$store.getters.SINGLE_DATE_CONFIG"
                        />
                    </div>
                </div>
                <div class="submit-section">
                    <button :disabled="isSending" @click.prevent="prev()"
                            class="btn btn-outline-info submit-btn pull-left"><i
                        class="fa fa-arrow-left "></i>
                        Previous
                    </button>
                    <button :disabled="formInvalid" class="btn btn-primary submit-btn pull-right">
                        <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                        <span v-else>Save</span>
                    </button>
                </div>
            </fieldset>
        </form>
    </MainModal>
</template>

<script>
    import MainModal from "../../shared/MainModal";
    import DirectiveAndResolution from "../../../models/smps/DirectiveAndResolution";
    import {deepClone, displayErrorMessage} from "../../../utils/helpers";
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        components: {
            MainModal,
            TinymceEditor: require('@tinymce/tinymce-vue').default,
        },
        created() {
            EventBus.$on("EDIT_DIRECTIVE_AND_RESOLUTION", this.editDirectiveAndResolution);
        },
        data() {
            return {
                directiveAndResolution: new DirectiveAndResolution(),
                isEditing: false,
                isSending: false,
                step: 1,
            }
        },
        computed: {
            ...mapGetters({
                workPlan: "ACTIVE_WORK_PLAN",
                directorates: "DIRECTORATES_OPTIONS",
            }),
            title() {
                return !!this.directiveAndResolution.id ? "Edit Directive and Resolution" : "New Directive and Resolution";
            },
            formInvalid() {
                return this.isSending || !(
                    !!this.directiveAndResolution.type &&
                    !!this.directiveAndResolution.title &&
                    !!this.directiveAndResolution.description &&
                    !!this.directiveAndResolution.dateReceived &&
                    !!this.directiveAndResolution.deadline &&
                    !!this.directiveAndResolution.sourceType &&
                    !!this.directiveAndResolution.responsibilityCentreId
                );
            }
        },
        methods: {
            prev() {
                this.step--;
            },
            next() {
                this.step++;
            },
            editDirectiveAndResolution(directiveAndResolution = null) {
                if (directiveAndResolution) {
                    this.directiveAndResolution = deepClone(directiveAndResolution);
                } else {
                    this.directiveAndResolution = new DirectiveAndResolution();
                }
                this.isEditing = true;
            },
            async saveDirectiveAndResolution() {
                try {
                    this.isSending = true;
                    if (!this.directiveAndResolution.workPlanId) {
                        this.directiveAndResolution.workPlanId = this.workPlan.id;
                    }
                    let response = await this.$store.dispatch('SAVE_DIRECTIVE_AND_RESOLUTION', this.directiveAndResolution);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('DIRECTIVE_AND_RESOLUTION_SAVED');
                    this.resetForm();
                } catch (error) {
                    await displayErrorMessage(error);
                    this.isSending = false;
                }
            },
            resetForm() {
                this.directiveAndResolution = new DirectiveAndResolution();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
