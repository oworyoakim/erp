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
          <span>{{education.start_month}} {{education.start_year}}</span>
          -
          <span
            v-if="!!education.end_month && !!education.end_year"
          >{{education.end_month}} {{education.end_year}}</span>
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
import { EventBus } from "../../../app";

export default {
  props: {
    educations: Array
  },
  methods: {
    editEducation(education) {
      EventBus.$emit("editEducation", education);
    }
  }
};
</script>

<style scoped>
</style>
