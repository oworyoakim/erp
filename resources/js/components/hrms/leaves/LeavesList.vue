<template>
    <table class="table table-condensed table-striped" ref="leavesDataTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Duration (in Days)</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(leave,index) in leaves">
                        <td>{{index + 1}}</td>
                        <td>{{leave.leaveType.title}}</td>
                        <td>{{leave.startDate}}</td>
                        <td>{{leave.endDate}}</td>
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
