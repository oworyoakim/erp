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
            <td>{{ person.fullName }}</td>
            <td>
                <template v-if="!!person.relationship">
                    {{ person.relationship.title }}
                </template>
            </td>
            <td>{{ person.dob }}</td>
            <td>{{ person.phone }}</td>
            <td>{{ person.email }}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle"
                       href="#"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <template v-if="$store.getters.HAS_ANY_ACCESS(['employees.manage_related_persons_info'])">
                            <a href="javascript:void(0)" class="dropdown-item" @click="editRelatedPerson(person)"><i
                                class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a href="javascript:void(0)" class="dropdown-item" @click="deleteRelatedPerson(person)"><i
                                class="fa fa-trash-o m-r-5"></i> Delete</a>
                        </template>
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
        employeeId: {type: Number, required: true},
        relatedPersons: {type: Array, default: () => []}
    },
    methods: {
        editRelatedPerson(person = null) {
            EventBus.$emit('EDIT_RELATED_PERSON', person);
        },
        async deleteRelatedPerson(person) {

        },
    },
}
</script>

<style scoped>

</style>
