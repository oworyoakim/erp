<template>
    <h2>SPMS Dashboard</h2>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        props: ["title"],
        created() {
            this.getDashboardStatistics();
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                statisticsInfo: 'GET_DASHBOARD_STATISTICS',
            }),
        },
        methods: {
            async getDashboardStatistics() {
                try {

                } catch (error) {
                    console.error(error);
                    await swal({title: error, icon: 'error'});
                }
            },
        }
    }
</script>

<style scoped>

</style>
