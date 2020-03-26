<template>
    <!-- Person Modal -->
    <div ref="personModal" id="personModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Related Person Form</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveRelatedPerson">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Title<span class="text-danger">*</span></label>
                                <select v-model="relatedPerson.title" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="title in formSelectionOptions.titles" :value="title.slug">{{title.title}}</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>First Name<span class="text-danger">*</span></label>
                                <input v-model="relatedPerson.first_name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Middle Name</label>
                                <input v-model="relatedPerson.middle_name" class="form-control" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Last Name<span class="text-danger">*</span></label>
                                <input v-model="relatedPerson.last_name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Gender<span class="text-danger">*</span></label>
                                <select v-model="relatedPerson.gender" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="gender in formSelectionOptions.genders" :value="gender.slug">{{gender.title}}</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Relationship<span class="text-danger">*</span></label>
                                <select v-model="relatedPerson.relationship_id" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="relationship in formSelectionOptions.relationships" :value="relationship.id">
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
                                <input v-model="relatedPerson.mobile" class="form-control" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Email</label>
                                <input v-model="relatedPerson.email" class="form-control" type="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <div class="row">
                                    <label class="col-sm-9">Is Next of Kin?</label>
                                    <div class="col-sm-3">
                                        <input v-model="relatedPerson.is_next_of_kin" class="form-control" type="checkbox">
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
                            <button
                                :disabled="isSending || !(!!relatedPerson.title && !!relatedPerson.first_name && !!relatedPerson.last_name && !!relatedPerson.gender && !!relatedPerson.relationship_id)"
                                class="btn btn-primary submit-btn">Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Person Modal -->
</template>

<script>
    import RelatedPerson from "../../../models/hrms/RelatedPerson";
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        props: {
            employee_id: Number,
        },
        created() {
            EventBus.$on('editRelatedPerson', this.editRelatedPerson);
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'getFormSelections',
            }),
        },
        data() {
            return {
                relatedPerson: new RelatedPerson(),
                isSending: false,
            }
        },
        methods: {
            editRelatedPerson(person) {
                this.relatedPerson = person;
                $(this.$refs.personModal).modal('show');
            },
            async saveRelatedPerson() {
                try {
                    if(this.relatedPerson.emergency && (!!!this.relatedPerson.phone || !!this.relatedPerson.email)){
                        throw new Error('Emergency contact must have phone number and/or email address!');
                    }
                    this.isSending = true;
                    if (!!!this.relatedPerson.employee_id) {
                        this.relatedPerson.employee_id = this.employee_id;
                    }
                    let response = await this.$store.dispatch('saveRelatedPerson', this.relatedPerson);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('relatedPersonSaved');
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.relatedPerson = new RelatedPerson();
                $(this.$refs.personModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
