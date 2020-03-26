<template>
    <table class="table table-striped table-sm mb-0 directorates-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Directorate Name</th>
            <th>Description</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="directorate in directorates">
            <td>{{directorate.id}}</td>
            <td>{{directorate.title}}</td>
            <td>{{directorate.description}}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" :href="'/directorates/view/' + directorate.id"><i class="fa fa-eye m-r-5"></i> View</a>
                        <a @click="editDirectorate(directorate)" class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a @click="deleteDirectorate(directorate.id)" class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        props:{
            directorates: Array,
        },
        methods: {
            editDirectorate(directorate) {
                EventBus.$emit('editDirectorate',directorate);
            },
            async deleteDirectorate(id) {
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
                    if (isConfirm) {
                        let response = await this.$store.dispatch('DELETE_DIRECTORATE', id);
                        toastr.success(response);
                        EventBus.$emit('directorateDeleted');
                    }
                } catch (error) {
                    console.log(error);
                    console.error(error);
                }
            },
        },
    }
</script>

<style scoped>

</style>
