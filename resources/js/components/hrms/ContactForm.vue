<template>
    <MainModal :is-open="isEditing" title="Contacts Information" @modal-closed="resetForm()">
        <form @submit.prevent="saveContactInfo()" autocomplete="off">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Type</label>
                        <select v-model="contact.type" class="form-control select">
                            <option value="personal">Personal</option>
                            <option value="home">Home</option>
                            <option value="work">Work</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Kind</label>
                        <select v-model="contact.kind" class="form-control select">
                            <option value="phone">Phone</option>
                            <option value="email">Email</option>
                            <option value="fax">Fax</option>
                            <option value="extension">Extension</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-form-label">Contact </label>
                        <input v-model="contact.value" class="form-control"
                               :type="contact.kind === 'email' ? 'email' : 'text'">
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button type="submit"
                        :disabled="formInvalid"
                        class="btn btn-primary btn-block submit-btn">
                    <Spinner v-if="isSending"></Spinner>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </MainModal>
</template>

<script>
import MainModal from "../shared/MainModal";
import Contact from "../../models/Contact";
import Spinner from "../shared/Spinner";
import {EventBus} from "../../app";

export default {
    props: {
        contactable: {type: String, default: 'employee'},
        contactableId: {type: Number, required: true},
    },
    components: {Spinner, MainModal},
    computed: {
        formInvalid() {
            return this.isSending ||
                !(!!this.contact.kind && !!this.contact.type && !!this.contact.value);
        },
    },
    data() {
        return {
            isEditing: false,
            isSending: false,
            contact: new Contact(),
        }
    },
    created() {
        EventBus.$on('EDIT_CONTACT', this.editContact);
    },
    methods: {
        editContact(contact = null) {
            if (contact) {
                this.contact = contact;
            } else {
                this.contact = new Contact();
            }
            this.isEditing = true;
        },
        async saveContactInfo() {
            try {
                this.contact.contactable = this.contactable.split('-')
                    .map((value) => this.$options.filters.upperCaseFirst(value))
                    .join('');
                this.contact.contactableId = this.contactableId;
                let response = await this.$store.dispatch("SAVE_CONTACT_INFO", this.contact);
                toastr.success(response);
                this.resetForm();
                this.isSending = false;
                EventBus.$emit('CONTACT_INFO_SAVED');
            } catch (error) {
                let message = document.createElement('div');
                //message.innerHTML = error.trim('"');
                message.innerHTML = error;
                await swal({content: message, icon: 'error'});
                this.isSending = false;
            }
        },
        resetForm() {
            this.isEditing = false;
            this.isSending = false;
            this.contact = new Contact();
            $(".modal-backdrop").remove();
        },
    }
}
</script>

<style scoped>

</style>
