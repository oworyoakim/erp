<template>
    <table class="table table-nowrap">
        <thead class="thead-light">
        <tr>
            <th>Name</th>
            <th>Relationship</th>
            <th>Date of Birth</th>
            <th>Phone</th>
            <th>Email</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="person in relatedPersons">
            <td>{{person.fullName}}</td>
            <td>{{person.relationship.title}}</td>
            <td>{{person.dob}}</td>
            <td>{{person.mobile}}</td>
            <td>{{person.email}}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle"
                       href="#"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item" @click="editRelatedPerson(person)"><i
                            class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" @click="deleteRelatedPerson(person.id)"><i
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
            relatedPersons: Array
        },
        methods: {
            editRelatedPerson(person) {
                EventBus.$emit('editRelatedPerson', person);
            },
            async deleteRelatedPerson(id) {

            },
        },
    }
</script>

<style scoped>

</style>
