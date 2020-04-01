<template>
    <app-spinner v-if="isLoading || !objective"/>
    <div class="mt-2" v-else>
        <!-- Objective Details    -->
        <h1>{{objective.name}}</h1>
        <app-objective-form :plan-id="objective.planId"/>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        props: {
            objectiveId: String
        },
        created() {
            this.getObjectiveDetails()
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                objective: 'OBJECTIVE_DETAILS',
            }),
        },
        methods: {
            async getObjectiveDetails() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_OBJECTIVE_DETAILS', this.objectiveId);
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    await swal({title: error, icon: 'error'});
                    this.isLoading = false;
                }
            }
        }
    }
</script>

<style scoped>

</style>
