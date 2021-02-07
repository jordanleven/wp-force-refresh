<template>
  <div class="force-refresh-troubleshooting">
    <h4 class="header">
      Plugin Info
    </h4>
    <div class="force-refresh-troubleshooting__plugin-info">
      <PluginVersions
        v-for="({ label, version, versionRequired }, index) in troubleshootingInfo.versions"
        :key="index"
        :label="label"
        :version="version"
        :version-required="versionRequired"
      />
    </div>
  </div>
</template>

<script>
import VueTypes from 'vue-types';
import PluginVersions from '@/components/PluginVersions/PluginVersions.vue';

export default {
  name: 'AdminTroubleshooting',
  components: {
    PluginVersions,
  },
  props: {
    troubleshootingInfo: VueTypes.shape({
      versions: VueTypes.arrayOf(
        VueTypes.shape(PluginVersions.props),
      ),
    }),
  },
};
</script>

<style lang="scss" scoped>
@use '@/scss/utilities' as utils;
@use '@/scss/variables' as var;

.force-refresh-troubleshooting {
  width: 100%;
}

.force-refresh-troubleshooting__plugin-info {
  padding: var.$space-medium 0;
  text-align: left;
  border: 2px solid var.$light_grey;
  border-radius: 10px;
  background-color: white;
}
</style>
