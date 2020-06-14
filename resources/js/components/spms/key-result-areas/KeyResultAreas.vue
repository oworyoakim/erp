<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin"></span>
    <table v-else class="table table-striped custom-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th class="text-center">Rank</th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
         <tr v-if="keyResultAreas.length === 0">
             <td colspan="5" class="text-center">No objectives</td>
         </tr>
        <template v-else>
            <tr v-for="keyResultArea in keyResultAreas" :key="keyResultArea.id">
                <td><a href="javascript:void(0)" @click="viewKeyResultArea(keyResultArea)">{{keyResultArea.name}}</a></td>
                <td>{{$stringLimit(keyResultArea.description)}}</td>
                <td class="text-center">
                    {{keyResultArea.rank}}
                </td>
                <td class="text-center">
                    <a @click="editKeyResultArea(keyResultArea)" class="" href="#" data-toggle="modal"
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
                keyResultAreas: "KEY_RESULT_AREAS"
            }),
        },
        data() {
            return {
                isLoading: false,
            }
        },
        created() {
            this.$nextTick(this.getKeyResultAreas);
            EventBus.$on(['KEY_RESULT_AREA_SAVED'], this.getKeyResultAreas);
        },
        watch: {
            planId(newValue, oldValue) {
                this.getKeyResultAreas();
                // console.log("old>>>",oldValue, "new>>",newValue)
            }
        },
        methods: {
            async getKeyResultAreas() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_KEY_RESULT_AREAS', {planId: this.planId});
                    this.isLoading = false;
                } catch (error) {
                    this.isLoading = false;
                }
            },
            editKeyResultArea(keyResultArea = null) {
                EventBus.$emit("EDIT_KEY_RESULT_AREA", keyResultArea);
            },
            viewKeyResultArea(keyResultArea) {
                window.location.href = '/spms/key-result-areas/show/' + keyResultArea.id;
            },
        }
    }
</script>

<style scoped>

</style>
