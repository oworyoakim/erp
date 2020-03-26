<template>
  <div class="personal-info">
    <div class="profile-title-container">
      <div class="profile-title-highlight text-center">
        <i :class="iconClass"></i>
      </div>
      <div class="profile-title-text">
        <h4>{{title}}</h4>
      </div>
    </div>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    iconClass: String
  }
};
</script>

<style scoped>
.profile-title-container {
  display: flex;
  flex-direction: row;
}
.profile-title-highlight {
  background-color: #923a78;
  color: #fff;
  flex-grow: 1;
}
.profile-title-text {
  background-color: #172a56;
  color: #fff;
  flex-grow: 11;
  padding-left: 5px;
}
</style>
