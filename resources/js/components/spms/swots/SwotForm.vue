<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveSwot" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-3">Type</label>
                <div class="col-sm-9">
                    <select v-model="swot.type" class="form-control" :disabled="!!swot.id" required>
                        <option value="">Select...</option>
                        <option v-for="type in swotTypes" :key="`swot-type-${type.value}`" :value="type.value">
                            {{type.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Description</label>
                <div class="col-sm-9">
                    <TinymceEditor
                        :api-key="$store.getters.TINYMCE_API_KEY"
                        :init="{...$store.getters.EDITOR_CONFIG,hieght: 320}"
                        v-model="swot.description"
                    />
                </div>
            </div>

            <div class="submit-section">
                <button :disabled="formIsInvalid" class="btn btn-info btn-block add-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
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
        components:{
            TinymceEditor: require('@tinymce/tinymce-vue').default,
        },
        created() {
            EventBus.$on("EDIT_SWOT", this.editSwot);
        },
        computed: {
            ...mapGetters({
                plan: "ACTIVE_PLAN",
                swotTypes: "SWOT_TYPES",
            }),
            title() {
                return (!!this.swot.id) ? "Edit Swot" : "Add Swot";
            },
            formIsInvalid() {
                return this.isSending || !(!!this.swot.type && !!this.swot.description);
            },
        },
        data() {
            return {
                swot: new Swot(),
                isEditing: false,
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
                this.isEditing = true;
            },
            async saveSwot() {
                try {
                    this.isSending = true;
                    this.swot.planId = this.planId;
                    let response = await this.$store.dispatch("SAVE_SWOT", this.swot);
                    this.isSending = false;
                    toastr.success(response);
                    this.resetForm();
                    EventBus.$emit("SWOT_SAVED");
                } catch (error) {
                    console.error(error);
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.swot = new Swot();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
