<template>
    <table class="table table-condensed table-striped table-sm mb-0 departments-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Department Name</th>
            <th v-if="!!!directorateId">Directorate</th>
            <th>Description</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(department,index) in departments">
            <td>{{index + 1}}</td>
            <td>{{department.title}}</td>
            <td v-if="!!!directorateId">
                <template v-if="!!department.directorate">
                    <a v-if="$store.getters.HAS_ANY_ACCESS(['directorates.view','directorates.show'])" :href="'/hrms/directorates/view/' + department.directorateId">{{department.directorate.title}}</a>
                    <template v-else>{{department.directorate.title}}</template>
                </template>
            </td>
            <td>{{department.description}}</td>
            <td class="text-right">
                <template v-if="$store.getters.HAS_ANY_ACCESS(['departments.view','departments.show'])">
                    <a v-if="!!scope" class="btn btn-info btn-sm" title="View Details" :href="'/hrms/departments/view/' + department.id +'?scope='+scope"><i
                        class="fa fa-eye m-r-5"></i></a>
                    <a v-else class="btn btn-info btn-sm" title="View Details" :href="'/hrms/departments/view/' + department.id"><i
                        class="fa fa-eye m-r-5"></i></a>
                </template>
                <a v-if="$store.getters.HAS_ANY_ACCESS(['departments.update'])" @click="editDepartment(department)" class="btn btn-info btn-sm" title="Edit" href="javascript:void(0)"><i
                    class="fa fa-pencil m-r-5"></i></a>
                <a v-if="$store.getters.HAS_ANY_ACCESS(['departments.delete'])" @click="deleteDepartment(department)" class="btn btn-danger btn-sm" title="Delete" href="javascript:void(0)"><i
                    class="fa fa-trash-o m-r-5"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            departments: {
                type: Array,
                required: true
            },
            directorateId: Number,
            scope: String,
        },
        methods: {
            editDepartment(department) {
                EventBus.$emit('EDIT_DEPARTMENT', department);
            },
            async deleteDepartment(department) {
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
                        let response = await this.$store.dispatch('DELETE_DEPARTMENT', department.id);
                        toastr.success(response);
                        EventBus.$emit('DEPARTMENT_DELETED');
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
