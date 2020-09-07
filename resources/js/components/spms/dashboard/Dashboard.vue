<template>
    <div class="spms-dashboard table-responsive">
        <iframe width="100%"
                height="600"
                src="https://app.powerbi.com/view?r=eyJrIjoiM2Y4NzBjMTgtZTM0MC00MWEyLWI1OGEtZmVkOWQwYjUwZjk2IiwidCI6IjIwMTBhMjI3LWQ2YjctNGFjZS1iMzhiLTAyNDQ0ZDc0YjkwNCIsImMiOjh9"
                frameborder="0"
                allowFullScreen="true">
        </iframe>
    </div>
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
