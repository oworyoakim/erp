<template>
    <table class="table table-striped custom-table">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Size</th>
            <th>Type</th>
            <th>Modified</th>
            <th>Download</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="employeeDocument in employeeDocuments">
            <td>{{employeeDocument.id}}</td>
            <td>{{employeeDocument.title}}</td>
            <td>
                <span v-if="!!employeeDocument.documentType">
                    {{employeeDocument.documentType.title}}
                </span>
            </td>
            <td>{{employeeDocument.size}}KB</td>
            <td>{{employeeDocument.type}}</td>
            <td>{{$moment(employeeDocument.lastModifiedAt).format('MMM D YYYY HH:mm')}}</td>
            <td>
                <a :href="employeeDocument.path" target="_blank"
                   class="btn btn-default btn-sm btn-link"><i class="fa fa-download"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: {
            employeeDocuments: Array,
        },
        methods: {
            downloadDocument(path) {
                console.log(path);
            }
        }
    }
</script>

<style scoped>

</style>
