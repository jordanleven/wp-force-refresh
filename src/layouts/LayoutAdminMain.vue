<template>
  <div class="wrap">
    <h1 class="header" @click="headerClicked">
      Force Refresh
    </h1>
    <AdminNotification v-if="notificationMessage" :message="notificationMessage" @notification-closed="notificationWasClosed" />
    <div class="admin-section">
      <transition name="fade-and-scale__debugging">
        <AdminDebugging
          v-if="debuggingActive"
          class="admin-section__debugging"
        />
      </transition>
      <transition name="fade-and-scale__main">
        <AdminMain
          v-if="!debuggingActive"
          class="admin-section__main"
          :refresh-options="refreshOptions"
          :site-name="siteName"
          @refresh-requested="refreshSite"
          @options-were-updated="updateOptions"
        />
      </transition>
    </div>
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

/**
 * The number of clicks required before the debugging page show up.
 * @var {Number}
 */
const DEBUGGING_NUMBER_OF_CLICKS_REQUIRED_TO_VIEW = 3;

/**
 * The number of milliseconds before a single click expires (to ensure clicks are deliberate to enter
 * the debugging page).
 * @var {Number}
 */
const DEBUGGING_TIMOUT_IN_MS = 1000;

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
      debuggingNumberOfClicks: 0,
      debuggingPageIsActive: false,
      notificationMessage: null,
      options: null,
    };
  },
  computed: {
    debuggingActive() {
      return this.debuggingPageIsActive;
    },
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
    /**
     * Method used to handle when users are trying to invoke the debugging page. If the header is clicked
     * a certain number of times within a set interval, we'll reveal the debugging page.
     * @return  {void}
     */
    headerClicked() {
      this.debuggingNumberOfClicks += 1;

      if (this.debuggingNumberOfClicks >= DEBUGGING_NUMBER_OF_CLICKS_REQUIRED_TO_VIEW) {
        this.debuggingPageIsActive = true;
      }

      setTimeout(() => {
        this.debuggingNumberOfClicks -= 1;
      }, DEBUGGING_TIMOUT_IN_MS);
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
@use '@/scss/variables' as var;

.header {
  display: inline;
  user-select: none;
}

.admin-section {
  position: relative;
  padding-top: var.$space-medium;
}

.admin-section__main,
.admin-section__debugging {
  width: 100%;
  position: absolute;
}

.admin-section__main {
  z-index: 1;
}

.admin-section__debugging {
  z-index: 2;
}

@keyframes fade-and-scale-main {
  from {
    opacity: 0;
    transform: scale(0.5);
  }

  100% {
    opacity: 1;
  }
}

@keyframes fade-and-scale-debugging {
  from {
    opacity: 0;
    transform: scale(2) translateY(-100px);
  }

  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.fade-and-scale__main-enter-active,
.fade-and-scale__main-leave-active,
.fade-and-scale__debugging-enter-active,
.fade-and-scale__debugging-leave-active {
  animation-fill-mode: both;
}

.fade-and-scale__main-enter-active,
.fade-and-scale__main-leave-active {
  animation-name: fade-and-scale-main;
}

.fade-and-scale__main-enter-active {
  transition-delay: var.$transition-medium;
  animation-duration: var.$transition-medium;
}

.fade-and-scale__main-leave-active {
  animation-duration: var.$transition-medium;
}

.fade-and-scale__debugging-enter-active,
.fade-and-scale__debugging-leave-active {
  animation-duration: var.$transition-long;
  animation-name: fade-and-scale-debugging;
}

.fade-and-scale__debugging-enter-active {
  transition-delay: var.$transition-medium;
  animation-duration: var.$transition-long;
}

.fade-and-scale__debugging-leave-active {
  animation-duration: var.$transition-medium;
}

.fade-and-scale__main-leave-active,
.fade-and-scale__debugging-leave-active {
  animation-direction: reverse;
}
</style>
