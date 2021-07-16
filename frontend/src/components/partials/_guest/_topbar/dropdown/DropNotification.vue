<template>
  <div>
    <div
      class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b notification-bg"
    >
      <h3 class="kt-head__title">User Notifications</h3>
      <ul
        class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x"
        role="tablist"
      >
        <li class="nav-item">
          <a
            class="nav-link active show"
            data-toggle="tab"
            href="#topbar_notifications_notifications"
            role="tab"
            aria-selected="true"
          >
            New Notifications
          </a>
        </li>
      </ul>
    </div>
    <div class="tab-content">
      <div
        class="tab-pane active show"
        id="topbar_notifications_notifications"
        role="tabpanel"
      >
        <div
          class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll notification-height"
        ></div>
        <div class="tab-pane" id="topbar_notifications_logs">
          <div class="kt-grid kt-grid--ver" style="min-height: 150px">
            <div
              class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle"
            >
              <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                All caught up!
                <br />No new notifications.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DropNotification',
  created() {},
  computed: {},
  methods: {},
};
</script>
<style lang="stylus" scoped>
// .notification-bg
  // background-image url('../../../../../../../../assets/images/notif-bg.jpg')
.notification-height
  max-height 300px
  overflow-y scroll
.kt-notification .kt-notification__item:after
  content none
</style>
