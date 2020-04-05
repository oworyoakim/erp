<template>
    <!-- Add Plan Modal -->
    <div ref="objectiveModal" id="objectiveModal" class="modal custom-modal fade"
         role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Objective</h5>
                    <button
                        @click="closePreview()"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveObjective">
                        <div class="form-group row">
                            <label class="col-sm-4">
                                Name
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" v-model="objective.name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Description</label>
                            <div class="col-sm-8">
                                <textarea v-model="objective.description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Rank</label>
                            <div class="col-sm-8">
                                <select v-model="objective.rank" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="i in 10" :key="i" :value="i">{{i}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button :disabled="isSending" class="btn btn-info add-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Plan Modal -->
</template>
<script>
    import Plan from "../../../models/smps/Plan";
    import Objective from "../../../models/smps/Objective";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";
    // import {mapGetters} from "vuex";

    export default {
        // computed: {
        //     ...mapGetters({
        //         plan: "ACTIVE_PLAN",
        //     }),
        // },
        props: {
            planId: {
                type: Number,
                required: true
            }
        },
        created() {
            EventBus.$on('EDIT_OBJECTIVE', this.editObjective);
        },
        data() {
            return {
                isSending: false,
                objective: new Objective(),
            }
        },
        methods: {
            editObjective(objective=null) {
                // this.objective.planId = this.plan.id;
                if (objective) {
                    this.objective = deepClone(objective);
                } else {
                    this.objective = new Objective();
                }
                $(this.$refs.objectiveModal).modal('show');
            },
            async saveObjective() {
                try {
                    this.isSending = true;
                    this.objective.planId=this.planId;
                    let response = await this.$store.dispatch('SAVE_OBJECTIVE', this.objective);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OBJECTIVE_SAVED');
                    this.closePreview();
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.objective = new Objective();
                $(this.$refs.objectiveModal).modal('hide');
                $(".modal-backdrop").remove();
            }
        }
    }
</script>

<style scoped>

</style>
