<template>
  <div id="dashboard">
    <top-widgets />
    <div class="row">
      <div class="col-lg-4">
        <video-day />
      </div>
      <div class="col-lg-8">
        <video-table />
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <webinar-cal />
      </div>
    </div>
  </div>
</template>

<script>
import TopWidgets from '../components/dashboard/TopWidgets.vue';
import VideoDay from '../components/dashboard/VideoDay.vue';
import VideoTable from '../components/dashboard/VideoTable.vue';
import WebinarCal from '../components/dashboard/WebinarCal.vue';
import { includes as _includes, forEach as _forEach } from 'lodash-es';

export default {
  name: 'Home',
  components: {
    TopWidgets,
    VideoDay,
    VideoTable,
    WebinarCal,
  },
};
</script>
