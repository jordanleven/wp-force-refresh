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
          <TroubleshootingVersionsList :versions="troubleshootingInfo.versions" />
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
    <hr>
    <h4>
      Console
    </h4>
    <TroubleshootingConsole />
    <hr>
    <button class="button-primary" @click="exitTroubleshooting">
      Exit Troubleshooting
    </button>
  </div>
</template>

<script>
import VueTypes from 'vue-types';
import TroubleshootingConsole from '@/components/TroubleshootingConsole/TroubleshootingConsole.vue';
import TroubleshootingSettings from '@/components/TroubleshootingSettings/TroubleshootingSettings.vue';
import TroubleshootingVersionsList from '@/components/TroubleshootingVersionsList/TroubleshootingVersionsList.vue';

export default {
  name: 'AdminTroubleshooting',
  components: {
    TroubleshootingConsole,
    TroubleshootingSettings,
    TroubleshootingVersionsList,
  },
  props: {
    troubleshootingInfo: VueTypes.shape({
      settings: TroubleshootingSettings.props.settings,
      versions: TroubleshootingVersionsList.props.versions,
    }),
  },
  methods: {
    exitTroubleshooting() {
      this.$emit('exit-troubleshooting');
    },
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
