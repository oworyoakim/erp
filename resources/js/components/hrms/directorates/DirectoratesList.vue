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
        <tr v-for="(directorate, index) in directorates">
            <td>{{index + 1}}</td>
            <td>{{directorate.title}}</td>
            <td>{{directorate.description}}</td>
            <td class="text-right">
                <a v-if="$store.getters.HAS_ANY_ACCESS(['directorates.show','directorates.view'])"  class="btn btn-info btn-sm" title="View Details" :href="'/hrms/directorates/view/' + directorate.id"><i
                    class="fa fa-eye m-r-5"></i></a>
                <a v-if="$store.getters.HAS_ANY_ACCESS(['directorates.update'])"  @click="editDirectorate(directorate)" class="btn btn-info btn-sm" title="Edit" href="javascript:void(0)"><i
                    class="fa fa-pencil m-r-5"></i></a>
                <a v-if="$store.getters.HAS_ANY_ACCESS(['directorates.delete'])"  @click="deleteDirectorate(directorate)" class="btn btn-danger btn-sm" title="Delete" href="javascript:void(0)"><i
                    class="fa fa-trash-o m-r-5"></i></a>
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
            editDirectorate(directorate = null) {
                EventBus.$emit('EDIT_DIRECTORATE',directorate);
            },
            async deleteDirectorate(directorate) {
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
                        let response = await this.$store.dispatch('DELETE_DIRECTORATE', directorate.id);
                        toastr.success(response);
                        EventBus.$emit('DIRECTORATE_DELETED');
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
