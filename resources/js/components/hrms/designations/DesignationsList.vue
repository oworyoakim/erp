<template>
    <table class="table table-striped custom-table mb-0 designations-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Title</th>
            <th>Holders</th>
            <th>Department</th>
            <th>Directorate</th>
            <th>Summary</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="designation in designations">
            <td>{{designation.id}}</td>
            <td>{{designation.title}}</td>
            <td>{{designation.max_holders}}</td>
            <td>{{designation.department ? designation.department.title : ''}}</td>
            <td>{{designation.directorate ? designation.directorate.title : ''}}</td>
            <td>{{designation.summary}}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a @click="editDesignation(designation)" class="dropdown-item" href="#"><i
                            class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a @click="deleteDesignation(designation.id)" class="dropdown-item" href="#"><i
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
            designations: Array,
            scope: String,
        },
        methods: {
            editDesignation(designation) {
                EventBus.$emit('editDesignation', designation);
            },
            async deleteDesignation(id) {
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
                        let response = await this.$store.dispatch('DELETE_DESIGNATION', id);
                        toastr.success(response);
                        EventBus.$emit('designationDeleted');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
