<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="related-person-form">
        <form @submit.prevent="saveRelatedPerson">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Title<span class="text-danger">*</span></label>
                    <select v-model="relatedPerson.title" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="title in formSelectionOptions.titles" :value="title.slug">
                            {{title.title}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>First Name<span class="text-danger">*</span></label>
                    <input v-model="relatedPerson.firstName" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Middle Name</label>
                    <input v-model="relatedPerson.middleName" class="form-control" type="text">
                </div>
                <div class="col-sm-6">
                    <label>Last Name<span class="text-danger">*</span></label>
                    <input v-model="relatedPerson.lastName" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Gender<span class="text-danger">*</span></label>
                    <select v-model="relatedPerson.gender" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="gender in formSelectionOptions.genders" :value="gender.slug">
                            {{gender.title}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Relationship<span class="text-danger">*</span></label>
                    <select v-model="relatedPerson.relationshipId" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="relationship in formSelectionOptions.relationships"
                                :value="relationship.id">
                            {{relationship.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Date of Birth</label>
                    <input v-model="relatedPerson.dob" class="form-control" type="date">
                </div>
                <div class="col-sm-6">
                    <label>National ID</label>
                    <input v-model="relatedPerson.nin" class="form-control" placeholder="NIN" type="text">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Mobile</label>
                    <input v-model="relatedPerson.phone" class="form-control" v-bind:class="{'is-invalid': !!relatedPerson.emergency && !relatedPerson.phone}" type="text">
                </div>
                <div class="col-sm-6">
                    <label>Email</label>
                    <input v-model="relatedPerson.email" class="form-control" v-bind:class="{'is-invalid': !!relatedPerson.emergency && !relatedPerson.email}" type="email">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <div class="row">
                        <label class="col-sm-9">Is Next of Kin?</label>
                        <div class="col-sm-3">
                            <input v-model="relatedPerson.isNextOfKin" class="form-control"
                                   type="checkbox">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <label class="col-sm-9">Is Emergency Contact?</label>
                        <div class="col-sm-3">
                            <input v-model="relatedPerson.emergency" class="form-control" type="checkbox">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <label class="col-sm-9">Is Dependant?</label>
                        <div class="col-sm-3">
                            <input v-model="relatedPerson.dependant" class="form-control" type="checkbox">
                        </div>
                    </div>
                </div>
            </div>
            <div class="submit-section text-right">
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import {mapGetters} from "vuex";
    import RelatedPerson from "../../../models/hrms/RelatedPerson";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        props: {
            employeeId: {type: Number, required: true},
        },
        created() {
            EventBus.$on('EDIT_RELATED_PERSON', this.editRelatedPerson);
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            title() {
                return (!!this.relatedPerson.id) ? "Edit Relative Info" : "New Relative";
            },
            formInvalid() {
                return this.isSending || !(!!this.relatedPerson.title && !!this.relatedPerson.firstName && !!this.relatedPerson.lastName && !!this.relatedPerson.gender && !!this.relatedPerson.relationshipId && (!this.relatedPerson.emergency || (!!this.relatedPerson.phone && !!this.relatedPerson.email)));
            },
        },
        data() {
            return {
                relatedPerson: new RelatedPerson(),
                isSending: false,
                isEditing: false,
            }
        },
        methods: {
            editRelatedPerson(person = null) {
                if (!!person) {
                    this.relatedPerson = deepClone(person);
                } else {
                    this.relatedPerson = new RelatedPerson();
                    this.relatedPerson.employeeId = this.employeeId;
                }
                this.isEditing = true;
            },
            async saveRelatedPerson() {
                try {
                    if (this.relatedPerson.emergency && !(!!this.relatedPerson.phone && !!this.relatedPerson.email)) {
                        throw new Error('Emergency contact must have phone number and/or email address!');
                    }
                    this.isSending = true;
                    if (!!!this.relatedPerson.employeeId) {
                        this.relatedPerson.employeeId = this.employeeId;
                    }
                    let response = await this.$store.dispatch('SAVE_RELATED_PERSON', this.relatedPerson);
                    toastr.success(response);
                    this.isSending = false;
                    this.resetForm();
                    EventBus.$emit('RELATED_PERSON_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.relatedPerson = new RelatedPerson();
                this.relatedPerson.employeeId = this.employeeId;
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
