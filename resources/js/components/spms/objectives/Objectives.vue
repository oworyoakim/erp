<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin"></span>
    <table v-else class="table table-striped custom-table">
        <thead>
        <tr>
            <th>Name</th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
         <tr v-if="objectives.length === 0">
             <td colspan="2" class="text-center">No objectives</td>
         </tr>
        <template v-else>
            <tr v-for="objective in objectives" :key="objective.id">
                <td><a href="javascript:void(0)" @click="viewObjective(objective)">{{objective.name}}</a></td>
                <td class="text-center">
                    <a @click="editObjective(objective)" class="" href="#" data-toggle="modal"
                       data-target="#edit_project"><i class="fa fa-pencil m-r-5"></i></a>
                    <a class="" href="#" data-toggle="modal" data-target="#delete_project"><i
                        class="fa fa-trash-o m-r-5"></i></a>
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            planId: {
                type: Number,
                required: true
            }
        },
        computed: {
            ...mapGetters({
                objectives: "OBJECTIVES"
            }),
        },
        data() {
            return {
                isLoading: false,
            }
        },
        created() {
            this.$nextTick(this.getObjectives);
            EventBus.$on(['OBJECTIVE_SAVED'], this.getObjectives);
        },
        watch: {
            planId(newValue, oldValue) {
                this.getObjectives();
                // console.log("old>>>",oldValue, "new>>",newValue)
            }
        },
        methods: {
            async getObjectives() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_OBJECTIVES', {planId: this.planId});
                    this.isLoading = false;
                } catch (error) {
                    this.isLoading = false;
                }
            },
            editObjective(objective = null) {
                EventBus.$emit("EDIT_OBJECTIVE", objective);
            },
            viewObjective(objective) {
                // console.log(">>>>ccc<<<<")
                // EventBus.$emit("EDIT_OBJECTIVE", objective);
                window.location.href = '/spms/objectives/show/' + objective.id;
            },
        }
    }
</script>

<style scoped>

</style>
