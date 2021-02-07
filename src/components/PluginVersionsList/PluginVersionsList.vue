<template>
  <div>
    <PluginVersions
      v-for="({ label, version, versionRequired }, index) in versions"
      :key="index"
      :label="label"
      :version="version"
      :version-required="versionRequired"
    />
  </div>
</template>

<script>
import VueTypes from 'vue-types';
import PluginVersions from '@/components/PluginVersions/PluginVersions.vue';

export default {
  name: 'PluginVersionsList',
  components: {
    PluginVersions,
  },
  props: {
    versions: VueTypes.arrayOf(
      VueTypes.shape(PluginVersions.props),
    ),
  },
};
</script>
