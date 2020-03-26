<template>
  <table class="table table-bordered">
    <thead class="thead-light">
      <tr>
        <th>Period</th>
        <th>Company</th>
        <th>Position</th>
        <th>Description</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="experience in experiences" :key="experience.id">
        <td>
          <span>{{experience.start_month}} {{experience.start_year}}</span>
          -
          <span
            v-if="!!experience.end_month && !!experience.end_year"
          >{{experience.end_month}} {{experience.end_year}}</span>
          <span v-else>Present</span>
        </td>
        <td>{{experience.company}}</td>
        <td>{{experience.position}}</td>
        <td>{{experience.description}}</td>
        <td>
          <a
            href="javascript:void(0)"
            class="edit-icon"
            data-toggle="modal"
            @click="editExperience(experience)"
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
    experiences: Array
  },
  methods: {
    editExperience(experience) {
      EventBus.$emit("editExperience", experience);
    }
  }
};
</script>

<style scoped>
</style>
