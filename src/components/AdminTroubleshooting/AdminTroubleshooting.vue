<template>
  <div class="troubleshooting-container">
    <h2 class="header">
      Troubleshooting
    </h2>
  </div>
</template>

<script>
export default {
  name: 'AdminTroubleshooting',
};
</script>

<style lang="scss" scoped>
@use '@/scss/utilities' as utils;
@use '@/scss/variables' as var;

.troubleshooting-container {
  width: 100%;
  padding: var.$space-small var.$space-medium;
  text-align: left;
  border: 2px solid var.$light_grey;
  border-radius: 10px;
  background-color: white;
}
</style>
