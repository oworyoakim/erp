<template>
    <div class="profile-widget">
        <div class="profile-img">
            <a href="#" class="avatar"><img :src="employee.avatar" alt="photo"></a>
        </div>
        <div class="dropdown profile-action">
            <a href="javascript:void(0)" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                class="material-icons">more_vert</i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="javascript:void(0)" @click="editEmployee()">
                    <i class="fa fa-pencil m-r-5"></i> Edit</a>
                <a class="dropdown-item" href="javascript:void(0)">
                    <i class="fa fa-trash-o m-r-5"></i> Delete</a>
            </div>
        </div>
        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a :href="'/hrms/employees/profile/'+employee.username"
                                                           target="_blank">{{ employee.fullName }}</a>
        </h4>
        <div v-if="!!employee.designation" class="small text-muted">{{ employee.designation.title }}</div>
    </div>
</template>

<script>
import {EventBus} from "../../../app";

export default {
    props: {
        employee: {type: Object, required: true}
    },
    methods: {
        editEmployee() {
            EventBus.$emit('EDIT_EMPLOYEE', this.employee);
        },
    }
}
</script>

<style scoped>

</style>
