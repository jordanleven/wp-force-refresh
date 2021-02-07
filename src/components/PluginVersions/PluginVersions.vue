<template>
  <dl
    class="plugin-info"
    :class="pluginInfoClasses"
  >
    <dt>
      <span v-if="versionRequired" class="version-status">
        <font-awesome-icon
          class="version-status__icon"
          :title="versionStatusMessage"
          :icon="versionStatus"
        />
      </span>
      {{ label }} Version:
    </dt>
    <dd>{{ version }}</dd>
  </dl>
</template>

<script>
import { library } from '@fortawesome/fontawesome-svg-core';
import { faCheckCircle, faTimesCircle } from '@fortawesome/free-solid-svg-icons';
import compareVersions from 'compare-versions';
import VueTypes from 'vue-types';

library.add([faCheckCircle, faTimesCircle]);

export default {
  name: 'PluginVersions',
  props: {
    label: VueTypes.string.isRequired,
    version: VueTypes.string.isRequired,
    versionRequired: VueTypes.oneOfType([String, null]),
  },
  computed: {
    pluginInfoClasses() {
      return [
        this.versionIsOutdated && 'plugin-info--is-outdated',
      ];
    },
    versionIsOutdated() {
      return compareVersions(this.version, this.versionRequired) === -1;
    },
    versionStatus() {
      return this.versionIsOutdated ? faTimesCircle : faCheckCircle;
    },
    versionStatusMessage() {
      return this.versionIsOutdated
        ? `Your version of ${this.label} is outdated. Please update to version ${this.versionRequired}.`
        : `Your version of ${this.label} is up-to-date.`;
    },
  },
  methods: {
    compareVersion() {
      return false;
    },
  },
};
</script>

<style lang="scss" scoped>
@use '@/scss/utilities' as utils;
@use '@/scss/variables' as var;

$ICON_SIZE: 1.125rem;

.plugin-info {
  padding: var.$space-medium;
  font-size: 0.825rem;
  margin: 0;
  position: relative;

  &:nth-child(odd) {
    background-color: rgba(var.$medium-grey, 0.5);
  }

  // &.plugin-info--error {
  //   background-color: rgba(var.$red, 0.5);
  // }
}

.version-status {
  position: absolute;
  top: 0;
  bottom: 0;
  margin: auto;
  height: 100%;
  left: var.$space-medium;
  font-size: $ICON_SIZE;
  color: var.$status-success;
  display: flex;
  align-items: center;
  justify-content: center;

  &::before {
    content: '';
    display: block;
    border-radius: 100%;
    height: calc(#{$ICON_SIZE} - 2px);
    width: calc(#{$ICON_SIZE} - 2px);
    background-color: #fff;
    top: 0;
    bottom: 0;
    margin: 0;
    left: 0;
    z-index: 1;
  }
}

.version-status__icon {
  position: absolute;
  z-index: 2;
  cursor: help;

  &.fa-times-circle {
    color: var.$status-error;
  }
}

dt {
  margin-left: 1.825rem;
  display: inline-block;
}

dd {
  display: inline;
  @include utils.typeface-code();
}
</style>
