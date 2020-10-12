<template>
    <div class="card profile-box flex-fill">
        <div class="card-body">
            <h1 class="card-title">
                Contacts
                <a @click="editContact()"
                   class="edit-icon"
                   href="#">
                    <i class="fa fa-plus"></i>
                </a>
            </h1>
            <template v-if="personalContacts.length > 0">
                <h4 class="section-title">Personal</h4>
                <ul class="personal-info">
                    <li v-for="contact in personalContacts">
                        <div class="title">{{ contact.kind | upperCaseFirst }}</div>
                        <div class="text">
                            {{ contact.value }}
                            <a @click="editContact(contact)"
                               class="edit-icon"
                               href="javascript:void(0)">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </template>
            <template v-if="homeContacts.length > 0">
                <hr>
                <h4 class="section-title">Home</h4>
                <ul class="personal-info">
                    <li v-for="contact in homeContacts">
                        <div class="title">{{ contact.kind | upperCaseFirst }}</div>
                        <div class="text">
                            {{ contact.value }}
                            <a @click="editContact(contact)"
                               class="edit-icon"
                               href="javascript:void(0)">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </template>
            <template v-if="workContacts.length > 0">
                <hr>
                <h4 class="section-title">Work</h4>
                <ul class="personal-info">
                    <li v-for="contact in workContacts">
                        <div class="title">{{ contact.kind | upperCaseFirst }}</div>
                        <div class="text">
                            {{ contact.value }}
                            <a @click="editContact(contact)"
                               class="edit-icon"
                               href="javascript:void(0)">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </template>
            <ContactForm :contactable="contactable" :contactable-id="contactableId"/>
        </div>
    </div>
</template>

<script>
import ContactForm from "../ContactForm";
import {EventBus} from "../../../app";

export default {
    components: {ContactForm},
    props: {
        contactable: {type: String, default: 'employee'},
        contactableId: {type: Number, required: true},
        contacts: {type: Array, default: () => []},
    },
    created() {
    },
    data() {
        return {
            step: 0,

        }
    },
    computed: {
        personalContacts() {
            return this.contacts.filter((contact) => contact.type === 'personal');
        },
        homeContacts() {
            return this.contacts.filter((contact) => contact.type === 'home');
        },
        workContacts() {
            return this.contacts.filter((contact) => contact.type === 'work');
        },
    },
    methods: {
        editContact(contact = null) {
            EventBus.$emit('EDIT_CONTACT', contact);
        },
    }
}
</script>

<style scoped>

</style>
