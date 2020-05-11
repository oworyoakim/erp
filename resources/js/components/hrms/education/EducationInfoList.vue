<template>
    <table class="table table-bordered">
        <thead class="thead-light">
        <tr>
            <th>Institution</th>
            <th>Award</th>
            <th>Period</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="education in educations" :key="education.id">
            <td>{{education.institution}}</td>
            <td>{{education.qualification}}</td>
            <td>
                <span>{{education.startMonth}} {{education.startYear}}</span>
                -
                <span
                    v-if="!!education.endMonth && !!education.endYear">{{education.endMonth}} {{education.endYear}}</span>
                <span v-else>Present</span>
            </td>
            <td>
                <a
                    href="javascript:void(0)"
                    class="edit-icon"
                    data-toggle="modal"
                    @click="editEducation(education)"
                >
                    <i class="fa fa-pencil"></i>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            employeeId: Number,
            educations: {type: Array, required: true}
        },
        methods: {
            editEducation(education = null) {
                EventBus.$emit("EDIT_EDUCATION", education);
            }
        }
    };
</script>

<style scoped>
</style>
