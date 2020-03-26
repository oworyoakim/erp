<template>
    <div class="card profile-box flex-fill">
        <div class="card-body">
            <h2 class="card-title">Emergency Contact</h2>
            <h3 class="section-title">Primary</h3>
            <ul class="personal-info">
                <li>
                    <div class="title">Name</div>
                    <div class="text">John Doe</div>
                </li>
                <li>
                    <div class="title">Relationship</div>
                    <div class="text">Father</div>
                </li>
                <li>
                    <div class="title">Phone </div>
                    <div class="text">9876543210, 9876543210</div>
                </li>
            </ul>
            <hr>
            <h3 class="section-title">Secondary</h3>
            <ul class="personal-info">
                <li>
                    <div class="title">Name</div>
                    <div class="text">Karen Wills</div>
                </li>
                <li>
                    <div class="title">Relationship</div>
                    <div class="text">Brother</div>
                </li>
                <li>
                    <div class="title">Phone </div>
                    <div class="text">9876543210, 9876543210</div>
                </li>
            </ul>
        </div>
        <div v-if="step === 1">
            <h2>Contacts Information</h2>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Phone </label>
                        <input v-model="contact.mobile" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                        <input v-model="contact.email" class="form-control" type="email">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Fax </label>
                        <input v-model="contact.fax" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Type</label>
                        <select v-model="contact.type" class="form-control select">
                            <option value="personal">Personal</option>
                            <option value="home">Home</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button @click.prevent="saveContactInfo()" class="btn btn-pink"><i class="fa fa-save "></i> Save
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import Contact from "../../../models/Contact";

    export default {
        props:{
            contacts: Array,
        },
        created() {
        },
        data(){
            return {
                step: 0,
                contact: new Contact()
            }
        },
        methods:{
            async saveContactInfo(){
                console.log(this.contact);
            }
        }
    }
</script>

<style scoped>

</style>
