<template>
  <div class="wrap">
    <h1 class="header" @click="headerClicked">
      Force Refresh
    </h1>
    <AdminNotification
      v-if="notificationMessage"
      :message="notificationMessage"
      @notification-closed="notificationWasClosed"
    />
    <AdminDebugging
      v-if="debuggingActive"
    />
    <AdminMain
      v-else
      :refresh-options="refreshOptions"
      :site-name="siteName"
      @refresh-requested="refreshSite"
      @options-were-updated="updateOptions"
    />
  </div>
</template>

<script>
import { sprintf } from 'sprintf-js';
import VueTypes from 'vue-types';
import AdminDebugging from '@/components/AdminDebugging/AdminDebugging.vue';
import AdminMain from '@/components/AdminMain/AdminMain.vue';
import AdminNotification from '@/components/AdminNotification/AdminNotification.vue';
import { requestSiteRefresh, updateForceRefreshOptions } from '@/js/services/admin/refreshService';

const MESSAGE_SITE_REFRESHED_SUCCESS = "You've successfully refreshed your site. All connected browsers will refresh within %s seconds.";
const MESSAGE_SITE_REFRESHED_FAILURE = 'There was an issue refreshing your site. Please try again.';
const MESSAGE_SITE_SETTINGS_UPDATED_SUCCESS = "You've successfully updated settings for Force Refresh.";
const MESSAGE_SITE_SETTINGS_UPDATED_FAILURE = 'There was an issue updating your settings. Please try again.';

export default {
  name: 'LayoutAdminMain',
  components: {
    AdminDebugging,
    AdminMain,
    AdminNotification,
  },
  props: {
    nonce: VueTypes.string.isRequired,
    refreshOptions: VueTypes.object.isRequired,
    siteName: VueTypes.string.isRequired,
  },
  data() {
    return {
      debuggingActive: false,
      notificationMessage: null,
      options: null,
    };
  },
  created() {
    this.options = this.refreshOptions;
  },
  mounted() {
    this.checkForOptionsUpdated();
  },
  methods: {
    checkForOptionsUpdated() {
      if (window.location.href.indexOf('optionsUpdated') > -1) {
        this.notificationMessage = MESSAGE_SITE_SETTINGS_UPDATED_SUCCESS;
      }
    },
    headerClicked() {
      this.debuggingActive = !this.debuggingActive;
    },
    notificationWasClosed() {
      this.notificationMessage = null;
    },
    refreshSite() {
      const {
        nonce,
      } = this;

      requestSiteRefresh({ nonce })
        .then(({ success }) => {
          if (success) {
            this.notificationMessage = sprintf(MESSAGE_SITE_REFRESHED_SUCCESS, this.options?.refreshInterval);
          } else {
            this.notificationMessage = MESSAGE_SITE_REFRESHED_FAILURE;
          }
        });
    },
    updateOptions(updatedOptions) {
      const {
        nonce,
      } = this;

      updateForceRefreshOptions({
        nonce,
        refreshInterval: updatedOptions?.refreshInterval,
        showRefreshInMenuBar: updatedOptions?.showRefreshInMenuBar,
      })
        .then(({ success }) => {
          if (success) {
            // If we've updated the menu bar option, we need to manually reload the page in order
            // to have at item rerendered server side
            if (updatedOptions.showRefreshInMenuBar !== this.options.showRefreshInMenuBar) {
              window.location.search += '&optionsUpdated';
              return;
            }
            this.notificationMessage = MESSAGE_SITE_SETTINGS_UPDATED_SUCCESS;
            this.options = updatedOptions;
          } else {
            this.notificationMessage = MESSAGE_SITE_SETTINGS_UPDATED_FAILURE;
          }
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.header {
  display: inline;
}
</style>
