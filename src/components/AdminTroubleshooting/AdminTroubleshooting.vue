<template>
  <div class="force-refresh-troubleshooting">
    <h2 class="header">
      Troubleshooting
    </h2>
    <ul class="plugin-info__container">
      <li class="plugin-info">
        <h4 class="plugin-info__header">
          Installed Versions
        </h4>
        <div class="plugin-info__inner">
          <PluginVersionsList :versions="troubleshootingInfo.versions" />
        </div>
      </li>
      <li class="plugin-info">
        <h4 class="plugin-info__header">
          Site Settings
        </h4>
        <div class="plugin-info__inner">
          <TroubleshootingSettings :settings="troubleshootingInfo.settings" />
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import VueTypes from 'vue-types';
import PluginVersionsList from '@/components/PluginVersionsList/PluginVersionsList.vue';
import TroubleshootingSettings from '@/components/TroubleshootingSettings/TroubleshootingSettings.vue';

export default {
  name: 'AdminTroubleshooting',
  components: {
    PluginVersionsList,
    TroubleshootingSettings,
  },
  props: {
    troubleshootingInfo: VueTypes.shape({
      settings: TroubleshootingSettings.props.settings,
      versions: PluginVersionsList.props.versions,
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

.plugin-info__container {
  display: flex;
}

.plugin-info {
  width: 50%;

  &:nth-child(odd) {
    padding-right: var.$space-medium;
  }

  &:nth-child(even) {
    padding-left: var.$space-medium;
  }
}

.plugin-info__header {
  margin: 0;
}

.plugin-info__inner {
  margin-top: var.$space-small;
  padding: var.$space-medium 0;
  text-align: left;
  border: 2px solid var.$light_grey;
  border-radius: 10px;
  background-color: white;
}
</style>
