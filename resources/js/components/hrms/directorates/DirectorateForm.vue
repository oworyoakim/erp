<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveDirectorate">
            <div class="form-group row">
                <label class="col-md-4">Directorate Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="directorate.title" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Description</label>
                <div class="col-md-8">
                    <textarea v-model="directorate.description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">Submit</button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import Directorate from "../../../models/hrms/Directorate";
    import {EventBus} from "../../../app";

    export default {
        created() {
            EventBus.$on('EDIT_DIRECTORATE', this.editDirectorate);
        },
        data() {
            return {
                directorate: new Directorate(),
                isEditing: false,
                isSending: false,
            };
        },
        computed: {
            title() {
                if (!!this.directorate.id) {
                    return "Edit Directorate";
                }
                return "New Directorate";
            },
            formInvalid() {
                return this.isSending || !!!this.directorate.title;
            }
        },
        methods: {
            editDirectorate(directorate = null) {
                if (!!directorate) {
                    this.directorate = directorate;
                } else {
                    this.directorate = new Directorate();
                }
                this.isEditing = true;
            },
            async saveDirectorate() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DIRECTORATE', this.directorate);
                    toastr.success(response);
                    this.resetForm();
                    this.isSending = false;
                    EventBus.$emit('DIRECTORATE_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.directorate = new Directorate();
                this.isEditing = false;
                $(".modal-backdrop").remove();
            },
        },
    }
</script>

<style scoped>

</style>
