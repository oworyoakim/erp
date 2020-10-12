<template>
    <div class="employees-list">
        <table class="table table-condensed table-striped  table-sm" ref="employeesDataTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Employee ID</th>
                <th>Email</th>
                <th>Mobile</th>
                <th class="text-nowrap">Join Date</th>
                <th>Role</th>
                <th>Status</th>
                <th class="text-right no-sort">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="employee in employees.data">
                <td>
                    <EmployeeWidget
                        :key="employee.username"
                        :username="employee.username"
                        :name="employee.fullName"
                        :avatar="employee.avatar"
                        :position="employee.designation.title"
                    />
                </td>
                <td>{{employee.employeeNumber}}</td>
                <td>{{employee.email}}</td>
                <td>{{employee.mobile}}</td>
                <td>{{$moment(employee.joinDate).format('MMM D YYYY')}}</td>
                <td>{{employee.designation.title}}</td>
                <td>{{employee.employeeStatus}}</td>
                <td class="text-right">
                    <button class="btn btn-info btn-sm" type="button" @click="editEmployee(employee)">
                        <i class="fa fa-edit"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import EmployeeWidget from "./EmployeeWidget";
    import {EventBus} from "../../../app";

    export default {
        components: {EmployeeWidget},
        props: {
            employees: {type: Object, required: true},
        },
        mounted() {
            this.$nextTick(() => {
                //$(this.$refs.employeesDataTable).DataTable();
            });
        },
        methods: {
            editEmployee(employee) {
                EventBus.$emit('EDIT_EMPLOYEE', employee);
            },
        }
    }
</script>

<style scoped>

</style>
