<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin"></span>
    <table v-else class="table table-striped custom-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th class="text-center">Rank</th>
            <th class="text-center">Due Date</th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="objective in objectives">
            <td>{{objective.name}}</td>
            <td>{{objective.description}}</td>
            <td class="text-center">
                {{objective.rank}}
            </td>
            <td class="text-center">
                {{$moment(objective.dueDate).format('DD MMM, YYYY')}}
            </td>
            <td class="text-center">

            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        props: {
            planId: {
                type: Number,
                required: true
            }
        },
        computed:{
            ...mapGetters({
                objectives: "OBJECTIVES"
            }),
        },
        data(){
            return {
                isLoading: false,
            }
        },
        created() {
            this.$nextTick(this.getObjectives);
        },
        methods: {
            async getObjectives() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_OBJECTIVES', this.planId);
                    this.isLoading = false;
                } catch (error) {
                    this.isLoading = false;
                }
            }
        }
    }
</script>

<style scoped>

</style>
