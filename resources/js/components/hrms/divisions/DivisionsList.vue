<template>
    <table class="table table-condensed table-striped table-sm mb-0 divisions-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Division Name</th>
            <th>Department</th>
            <th>Directorate</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="division in divisions">
            <td>{{division.id}}</td>
            <td>{{division.title}}</td>
            <td>{{division.department ? division.department.title : ''}}</td>
            <td>{{division.directorate ? division.directorate.title : ''}}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a v-if="!!scope" class="dropdown-item" :href="'/divisions/view/' + division.id +'?scope='+ scope"><i
                            class="fa fa-eye m-r-5"></i> View</a>
                        <a v-else class="dropdown-item" :href="'/divisions/view/' + division.id"><i
                            class="fa fa-eye m-r-5"></i> View</a>
                        <a @click="editDivision(division)" class="dropdown-item" href="javascript:void(0)"><i
                            class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a @click="deleteDivision(division.id)" class="dropdown-item" href="javascript:void(0)"><i
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
            divisions: Array,
            scope: String,
        },
        methods: {
            editDivision(division) {
                EventBus.$emit('editDivision', division);
            },
            async deleteDivision(id) {
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
                        let response = await this.$store.dispatch('DELETE_DIVISION', id);
                        toastr.success(response);
                        EventBus.$emit('divisionDeleted');
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
