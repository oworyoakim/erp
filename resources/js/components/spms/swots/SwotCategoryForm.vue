<template>
    <!-- Add User Modal -->
    <div ref="swotCategoryModal" id="swotCategoryModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Swot Category Information</h5>
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
                    <form @submit.prevent="saveSwotCategory" autocomplete="off">
                        <div class="form-group row">
                            <label class="col-sm-3">Name</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       v-model="swotCategory.name"
                                       class="form-control"
                                       placeholder="Enter first name"
                                       required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3">Description</label>
                            <div class="col-sm-9">
                                <textarea type="text"
                                          v-model="swotCategory.description"
                                          class="form-control">
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
    import SwotCategory from "../../../models/smps/SwotCategory";

    export default {
        props: {
            planId: Number,
            required: true
        },
        created() {
            EventBus.$on("EDIT_SWOT_CATEGORY", this.editSwotCategory);
        },
        computed: {
            ...mapGetters({
                plan: "ACTIVE_PLAN",
                swotCategories: "SWOT_CATEGORIES",
            }),
            formIsInvalid() {
                return this.isSending || !!!this.swotCategory.name.trim();
            },
        },
        data() {
            return {
                swotCategory: new SwotCategory(),
                isSending: false,
            }
        },
        methods: {
            editSwotCategory(swotCategory = null) {
                if (swotCategory) {
                    this.swotCategory = swotCategory;
                } else {
                    this.swotCategory = new SwotCategory();
                }
                $(this.$refs.swotCategoryModal).modal("show");

            },
            async saveSwotCategory() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_SWOT_CATEGORY", this.swotCategory);
                    this.isSending = false;
                    toastr.success(response);
                    this.closeModal();
                    EventBus.$emit("SWOT_CATEGORY_SAVED");
                } catch (error) {
                    console.error(error);
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            closeModal() {
                this.swotCategory = new SwotCategory();
                $(this.$refs.swotCategoryModal).modal("hide");
                $(".modal-backdrop").remove();
            }
        }
    }
</script>

<style scoped>

</style>
