<template>
    <div class="profile-widget">
        <div class="profile-img">
            <a href="#" class="avatar"><img :src="employee.avatar" alt="photo"></a>
        </div>
        <div class="dropdown profile-action">
            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                class="material-icons">more_vert</i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_employee"><i
                    class="fa fa-pencil m-r-5"></i> Edit</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i
                    class="fa fa-trash-o m-r-5"></i> Delete</a>
            </div>
        </div>
        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a :href="'/hrms/employees/profile/'+employee.username">{{employee.fullName}}</a>
        </h4>
        <div class="small text-muted">{{employee.designation.title}}</div>
    </div>
</template>

<script>
    export default {
        props: {
            employee: {type: Object, required: true}
        }
    }
</script>

<style scoped>

</style>
