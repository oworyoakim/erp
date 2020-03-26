<template>
    <table class="table table-condensed table-striped" ref="leavesDataTable">
                    <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Duration (in Days)</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="leave in leaves">
                        <td>{{leave.leave_type.title}}</td>
                        <td>{{leave.start_date}}</td>
                        <td>{{leave.end_date}}</td>
                        <td>{{leave.duration}}</td>
                        <td>

                        </td>
                        <td class="text-right">

                        </td>
                    </tr>
                    </tbody>
                </table>
</template>

<script>
    export default {
        props: {
            leaves: Array,
        },
        mounted() {
            this.$nextTick(()=>{
               $(this.$refs.leavesDataTable).DataTable();
            });
        },
        methods: {

        }
    }
</script>
