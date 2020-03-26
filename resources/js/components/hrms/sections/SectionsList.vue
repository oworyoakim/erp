<template>
    <table class="table table-striped custom-table mb-0 sections-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Title</th>
            <th>Directorate</th>
            <th>Department</th>
            <th>Division</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="section in sections">
            <td>{{section.id}}</td>
            <td>{{section.title}}</td>
            <td>{{section.directorate ? section.directorate.title : ''}}</td>
            <td>{{section.department ? section.department.title : ''}}</td>
            <td>{{section.division ? section.division.title : ''}}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a @click="editSection(section)" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a @click="deleteSection(section.id)" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from '../../../app';

    export default {
        props: {
            sections: Array,
            scope: String,
        },
        methods: {
            editSection(section) {
                EventBus.$emit('editSection',section);
            },
            async deleteSection(id) {
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
                        let response = await this.$store.dispatch('DELETE_SECTION', id);
                        toastr.success(response);
                        EventBus.$emit('sectionDeleted');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
