<template>
    <table class="table table-condensed table-striped table-sm mb-0 divisions-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Division Name</th>
            <th v-if="!!!departmentId">Department</th>
            <th v-if="!!!directorateId">Directorate</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(division,index) in divisions">
            <td>{{index + 1}}</td>
            <td>{{division.title}}</td>
            <td v-if="!!!departmentId">
                <template v-if="!!division.department">
                    <a :href="'/hrms/departments/view/' + division.departmentId">{{division.department.title}}</a>
                </template>
            </td>
            <td v-if="!!!directorateId">
                <template v-if="!!division.directorate">
                    <a :href="'/hrms/directorates/view/' + division.directorateId">{{division.directorate.title}}</a>
                </template>
            </td>
            <td class="text-right">
                <a v-if="!!scope" class="btn btn-info btn-sm" title="View Details" :href="'/hrms/divisions/view/' + division.id +'?scope='+ scope"><i
                    class="fa fa-eye m-r-5"></i></a>
                <a v-else class="btn btn-info btn-sm" title="View Details" :href="'/hrms/divisions/view/' + division.id"><i
                    class="fa fa-eye m-r-5"></i></a>
                <a @click="editDivision(division)" class="btn btn-info btn-sm" title="Edit" href="javascript:void(0)"><i
                    class="fa fa-pencil m-r-5"></i></a>
                <a @click="deleteDivision(division)" class="btn btn-danger btn-sm" title="Delete" href="javascript:void(0)"><i
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
            divisions: {
                type: Array,
                required: true,
            },
            directorateId: Number,
            departmentId: Number,
            scope: String,
        },
        methods: {
            editDivision(division = null) {
                EventBus.$emit('EDIT_DIVISION', division);
            },
            async deleteDivision(division) {
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
                        let response = await this.$store.dispatch('DELETE_DIVISION', division.id);
                        toastr.success(response);
                        EventBus.$emit('DIVISION_DELETED');
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
