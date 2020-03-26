<template>
    <table class="table table-condensed table-striped table-sm mb-0 departments-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Department Name</th>
            <th>Directorate</th>
            <th>Description</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="department in departments">
            <td>{{department.id}}</td>
            <td>{{department.title}}</td>
            <td>{{department.directorate ? department.directorate.title : ''}}</td>
            <td>{{department.description}}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a v-if="!!scope" class="dropdown-item" :href="'/departments/view/' + department.id +'?scope='+scope"><i
                            class="fa fa-eye m-r-5"></i> View</a>
                        <a v-else class="dropdown-item" :href="'/departments/view/' + department.id"><i
                            class="fa fa-eye m-r-5"></i> View</a>
                        <a @click="editDepartment(department)" class="dropdown-item" href="javascript:void(0)"><i
                            class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a @click="deleteDepartment(department.id)" class="dropdown-item" href="javascript:void(0)"><i
                            class="fa fa-trash-o m-r-5"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            departments: Array,
            directorate_id: Number,
            scope: String,
        },
        methods: {
            editDepartment(department) {
                EventBus.$emit('editDepartment', department);
            },
            async deleteDepartment(id) {
                try {
                    let isConfirm = await swal({
                        title: 'Are you sure?',
                        text: "You will delete this record!",
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Yes'
                        ],
                        closeOnClickOutside: false
                    });
                    console.log(isConfirm);
                    if(isConfirm){
                        let response = await this.$store.dispatch('DELETE_DEPARTMENT', id);
                        toastr.success(response);
                        EventBus.$emit('departmentDeleted');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        },
    }
</script>

<style scoped>

</style>
