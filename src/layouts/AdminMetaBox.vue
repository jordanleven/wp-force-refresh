<template>
  <div class="force-refresh-admin-main">
    <div class="force-refresh-admin-main-inner">
      <transition name="notification-fade">
        <AdminNotification
          v-if="notificationVisible"
          :message="notificationMessage"
          @notification-closed="hideNotification"
        />
      </transition>
      <p>Force all users to manually reload the {{ postType }} "{{ postNameDecoded }}".</p>
      <button
        class="button button-primary"
        @click="refreshPage"
      >
        Refresh {{ postNameDecoded }}
      </button>
    </div>
  </div>
</template>

<script>
import VueTypes from 'vue-types';
import { AllHtmlEntities } from 'html-entities';
import { sprintf } from 'sprintf-js';
import { requestPostRefreshByPostID } from '@/js/services/admin/refreshService';
import AdminNotification from '@/components/AdminNotification/AdminNotification.vue';

const MESSAGE_REFRESH_SUCCESS = 'You\'ve successfully refreshed this page. All connected browsers will refresh within %s seconds.';

export default {
  name: 'AdminMetaBox',
  components: {
    AdminNotification,
  },
  props: {
    apiUrl: VueTypes.string.isRequired,
    nonce: VueTypes.string.isRequired,
    postId: VueTypes.number.isRequired,
    postName: VueTypes.string.isRequired,
    postType: VueTypes.string.isRequired,
  },
  data() {
    return {
      notificationVisible: false,
      refreshStatus: null,
      refreshInterval: null,
    };
  },
  computed: {
    postNameDecoded() {
      return AllHtmlEntities.decode(this.postName);
    },
    notificationMessage() {
      return sprintf(MESSAGE_REFRESH_SUCCESS, this.refreshInterval);
    },
  },
  methods: {
    hideNotification() {
      this.notificationVisible = false;
    },
    refreshPage() {
      const {
        postId,
        nonce,
      } = this;

      requestPostRefreshByPostID(postId, { nonce })
        .then(({ success, data }) => {
          this.refreshInterval = data.refresh_interval;
          this.notificationVisible = true;
          this.refreshStatus = success;
        });
    },
  },
};
</script>

<style lang="scss">
.notification-fade-enter-active,
.notification-fade-leave-active {
  transition: opacity 0.5s;
}

.notification-fade-enter,
.notification-fade-leave-to {
  opacity: 0;
}
</style>