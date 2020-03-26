<template>
    <div>
        <div v-if="step === 1">
            <form @submit.prevent="saveAddressInfo">
                <h2>Address Information</h2>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Region </label>
                            <input v-model="address.region" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Sub-Region </label>
                            <input v-model="address.subregion" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">District </label>
                            <input v-model="address.district" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Subcounty</label>
                            <input v-model="address.subcounty" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Parish</label>
                            <input v-model="address.parish" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Village</label>
                            <input v-model="address.village" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Street</label>
                            <input v-model="address.street" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Plot </label>
                            <input v-model="address.plot" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Type</label>
                            <select v-model="address.type" class="form-control select">
                                <option value="permanent">Permanent</option>
                                <option value="residence">Residence</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-pink"><i class="fa fa-save "></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Address from "../../../models/Address";

    export default {
        props: {
            addresses: Array,
        },
        created() {
        },
        data() {
            return {
                step: 0,
                address: new Address()
            }
        },
        methods: {
            async saveAddressInfo() {
                console.log(this.address);
            }
        },
    }
</script>

<style scoped>

</style>
