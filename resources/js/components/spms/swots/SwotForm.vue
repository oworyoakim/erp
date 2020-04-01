<template>
    <!-- Add User Modal -->
    <div ref="swotModal" id="swotModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Swot Information</h5>
                    <button
                        @click="closeModal()"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveSwot" autocomplete="off">
                        <div class="form-group row">
                            <label class="col-sm-3">Category</label>
                            <div class="col-sm-9">
                                <select v-model="swot.categoryId" class="form-control" :disabled="!!swot.id" required>
                                    <option value="">Select...</option>
                                    <option v-for="category in swotCategories" :key="category.id" :value="category.id">
                                        {{category.name}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3">Type</label>
                            <div class="col-sm-9">
                                <select v-model="swot.type" class="form-control" :disabled="!!swot.id" required>
                                    <option value="">Select...</option>
                                    <option v-for="type in swotTypes" :key="type.value" :value="type.value">
                                        {{type.text}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3">Name</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       v-model="swot.name"
                                       class="form-control"
                                       placeholder="Enter first name"
                                       required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3">Description</label>
                            <div class="col-sm-9">
                                <textarea type="text"
                                          v-model="swot.description"
                                          class="form-control"
                                          required>
                                </textarea>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button :disabled="formIsInvalid" class="btn btn-info btn-block add-btn">
                                <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                                <span v-else>Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add User Modal -->
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import Swot from "../../../models/smps/Swot";

    export default {
        props: {
            planId: Number,
            required: true
        },
        created() {
            EventBus.$on("EDIT_SWOT", this.editSwot);
        },
        computed: {
            ...mapGetters({
                plan: "ACTIVE_PLAN",
                swotCategories: "SWOT_CATEGORIES",
                swotTypes: "SWOT_TYPES",
            }),
            categories() {
                return this.swotCategories.map((category) => {
                    return {
                        text: category.name,
                        value: category.id,
                    }
                });
            },
            formIsInvalid() {
                return this.isSending || !(!!this.swot.name.trim() && !!this.swot.type && !!this.swot.categoryId);
            },
        },
        data() {
            return {
                swot: new Swot(),
                isSending: false,
            }
        },
        methods: {
            editSwot(swot = null) {
                if (swot) {
                    this.swot = swot;
                } else {
                    this.swot = new Swot();
                }
                $(this.$refs.swotModal).modal("show");

            },
            async saveSwot() {
                try {
                    this.isSending = true;
                    this.swot.planId = this.planId;
                    let response = await this.$store.dispatch("SAVE_SWOT", this.swot);
                    this.isSending = false;
                    toastr.success(response);
                    this.closeModal();
                    EventBus.$emit("SWOT_SAVED");
                } catch (error) {
                    console.error(error);
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            closeModal() {
                this.swot = new Swot();
                $(this.$refs.swotModal).modal("hide");
                $(".modal-backdrop").remove();
            }
        }
    }
</script>

<style scoped>

</style>
