<template>
  <v-card flat>
    <v-simple-table
      v-if="
        internetShop.phone_number != undefined &&
        internetShop.phone_number.calls != undefined &&
        internetShop.phone_number.calls.length
      "
    >
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">Date</th>
            <th class="text-center">From</th>
            <th class="text-center">Recording</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(item, index) in internetShop.phone_number.calls">
            <tr :key="item.name" class="p-3">
              <td v-if="item.start_at">
                {{ item.start_at | displayDateTime(internetShop.timezone, 1) }}
              </td>
              <td v-else>
                {{
                  item.created_at | displayDateTime(internetShop.timezone, 1)
                }}
              </td>
              <td class="text-center">{{ item.from }}</td>
              <td class="text-center">
                <template v-if="item.value != null">
                  <audio
                    v-if="item.value.includes('api.twilio.com')"
                    :src="item.value"
                    ref="audio"
                    controls
                  ></audio>
                  <audio
                    v-else
                    :src="`${aws_url}phone_numbers/${item.phone_number_id}/calls/${item.value}`"
                    ref="audio"
                    controls
                  ></audio>
                </template>
                <template v-else>
                  <span>No Recording</span>
                </template>
              </td>
              <td class="text-center">
                <!--                <button
                  type="button"
                  class="btn btn-label-dark btn-sm mr-3"
                  v-if="item.transcript !== null"
                  @click="toggleTranscript(index)"
                >
                  <i
                    class="far"
                    :class="
                      item.transcript_open ? 'fa-chevron-up' : 'fa-chevron-down'
                    "
                  />
                  {{ !item.transcript_open ? 'Open' : 'Close' }} transcript
                </button>-->
                <button
                  type="button"
                  class="btn btn-label-dark btn-sm btn-icon"
                  v-if="$auth.check() && isStaff"
                  @click="deleteCall(item.id, index)"
                >
                  <i class="far fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr :key="`transcript_${item.name}`" v-show="item.transcript_open">
              <td colspan="4" class="p-5">
                <h5>{{ item.transcript }}</h5>
              </td>
            </tr>
          </template>
        </tbody>
      </template>
    </v-simple-table>
    <p v-else class="lead ma-3 pa-2">No Call Logs Data</p>
  </v-card>
</template>
<script>
import { remove as _remove, some as _some } from 'lodash-es';
export default {
  name: 'CallLogs',
  data() {
    return {
      aws_url: '',
    };
  },

  props: {
    shop: {
      required: false,
      type: Object,
    },
    toggleTranscript: Function,
  },

  computed: {
    internetShop() {
      return this.shop;
    },
    currentUser() {
      return this.$auth.user();
    },
    roles() {
      return this.currentUser.roles;
    },
    isStaff() {
      return (
        _some(this.roles, { name: 'super-administrator' }) ||
        _some(this.roles, { name: 'secret-shopper' })
      );
    },
  },

  created() {
    this.$http.get('venv/aws-url').then((response) => {
      this.aws_url = response.data.aws_url;
    });
  },

  methods: {
    deleteCall(id, index) {
      console.log(id);
      this.$http
        .delete(`call/${id}`)
        .then((response) => {
          this.internetShop.phone_number.calls.splice(index, 1);
          this.$notify('success', 'Successfully deleted the call.');
        })
        .catch(() => {});
    },
  },
};
</script>
<style lang="stylus" scoped>
audio
  border-radius 0
  height 40px !important
  margin-top 5px
</style>
