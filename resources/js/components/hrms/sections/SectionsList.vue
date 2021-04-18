<template>
    <table class="table table-striped custom-table mb-0 sections-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Title</th>
            <th v-if="!!!directorateId">Directorate</th>
            <th v-if="!!!departmentId">Department</th>
            <th v-if="!!!divisionId">Division</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(section, index) in sections">
            <td>{{index + 1}}</td>
            <td>{{section.title}}</td>
            <td v-if="!!!directorateId">
                <template v-if="!!section.directorate">
                    <a  v-if="$store.getters.HAS_ANY_ACCESS(['directorates.view', 'directorates.show'])" :href="'/hrms/directorates/view/' + section.directorateId">{{section.directorate.title}}</a>
                    <template  v-else>{{section.directorate.title}}</template>
                </template>
            </td>
            <td v-if="!!!departmentId">
                <template v-if="!!section.department">
                    <a  v-if="$store.getters.HAS_ANY_ACCESS(['departments.view', 'departments.show'])" :href="'/hrms/departments/view/' + section.departmentId">{{section.department.title}}</a>
                    <template  v-else>{{section.department.title}}</template>
                </template>
            </td>
            <td v-if="!!!divisionId">
                <template v-if="!!section.division">
                    <a  v-if="$store.getters.HAS_ANY_ACCESS(['divisions.view','divisions.show'])" :href="'/hrms/divisions/view/' + section.divisionId">{{section.division.title}}</a>
                    <template v-else>{{section.division.title}}</template>
                </template>
            </td>
            <td class="text-right">
                <a  v-if="$store.getters.HAS_ANY_ACCESS(['sections.update'])" @click="editSection(section)" class="btn btn-info btn-sm" title="Edit" href="javascript:void(0)"><i
                    class="fa fa-pencil m-r-5"></i></a>
                <a  v-if="$store.getters.HAS_ANY_ACCESS(['sections.delete'])" @click="deleteSection(section)" class="btn btn-danger btn-sm" title="Delete" href="javascript:void(0)"><i
                    class="fa fa-trash-o m-r-5"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from '../../../app';

    export default {
        props: {
            sections: {type: Array,required: true},
            directorateId: Number,
            departmentId: Number,
            divisionId: Number,
            scope: String,
        },
        methods: {
            editSection(section = null) {
                EventBus.$emit('EDIT_SECTION',section);
            },
            async deleteSection(section) {
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
                        let response = await this.$store.dispatch('DELETE_SECTION', section.id);
                        toastr.success(response);
                        EventBus.$emit('SECTION_DELETED');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
