<template>
        <div ref="directorateModal" id="directorateModal" class="modal custom-modal fade" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Directorate Form</h5>
                        <button @click="closePreview()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveDirectorate">
                            <div class="form-group">
                                <label>Directorate Name <span class="text-danger">*</span></label>
                                <input v-model="directorate.title" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea v-model="directorate.description" class="form-control"></textarea>
                            </div>
                            <div class="submit-section">
                                <button :disabled="isSending || !!!directorate.title" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Directorate Modal -->
</template>

<script>
    import Directorate from "../../../models/hrms/Directorate";
    import {EventBus} from "../../../app";

    export default {
        created(){
            EventBus.$on('editDirectorate',this.editDirectorate);
        },
        data() {
            return {
                directorate: new Directorate(),
                isSending: false,
            };
        },
        methods: {
            editDirectorate(directorate) {
                this.directorate = directorate;
                console.log(directorate);
                $(this.$refs.directorateModal).modal('show');
            },
            async saveDirectorate() {
                try {
                    this.isSending =  true;
                    let response = await this.$store.dispatch('SAVE_DIRECTORATE',this.directorate);
                    toastr.success(response);
                    this.closePreview();
                    this.isSending = false;
                    EventBus.$emit('directorateSaved');
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.directorate = new Directorate();
                $(this.$refs.directorateModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
