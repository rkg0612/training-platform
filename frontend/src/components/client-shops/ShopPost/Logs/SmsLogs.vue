<template>
  <v-card flat>
    <div
      class="kt-chat"
      v-if="
        internetShop.phone_number != undefined &&
        internetShop.phone_number.sms != undefined &&
        internetShop.phone_number.sms.length
      "
    >
      <div class="kt-portlet kt-portlet--last">
        <div class="kt-portlet__body">
          <div class="kt-scroll kt-scroll--pull">
            <div class="kt-chat__messages kt-chat__messages--solid">
              <template v-for="(sms, index) in internetShop.phone_number.sms">
                <div
                  class="kt-chat__message"
                  :class="{
                    'kt-chat__message--success': !isOutbound(sms.from),
                    'kt-chat__message--right kt-chat__message--brand': isOutbound(
                      sms.from
                    ),
                  }"
                  :key="sms.id"
                >
                  <div class="kt-chat__user">
                    <button
                      type="button"
                      class="btn btn-label-dark btn-sm btn-icon"
                      v-if="$auth.check() && isStaff"
                      @click="deleteSms(sms.id, index)"
                    >
                      <i class="far fa-trash"></i>
                    </button>
                    <a href="#" class="kt-chat__username"> {{ sms.from }} </a>
                    <span class="kt-chat__datetime">
                      {{
                        sms.start_at | displayDateTime(internetShop.timezone, 1)
                      }}
                    </span>
                  </div>
                  <div class="kt-chat__text">
                    {{ sms.value }}

                    <div v-for="media in sms.medias" v-bind:key="media.id">
                      <img
                        v-if="isImage(media.name)"
                        :src="`${aws_url}phone_numbers/${internetShop.phone_number.id}/medias/${media.sid}`"
                        class="img-fluid"
                      />
                      <video
                        v-if="isVideo(media.name)"
                        class="embed-responsive-item"
                        :src="`${aws_url}phone_numbers/${internetShop.phone_number.id}/medias/${media.sid}`"
                        controls
                      ></video>
                      <audio
                        v-if="isAudio(media.name)"
                        class="embed-responsive-item"
                        :src="`${aws_url}phone_numbers/${internetShop.phone_number.id}/medias/${media.sid}`"
                        controls
                      ></audio>
                    </div>
                  </div>
                </div>
                <div style="clear: both" :key="`smsclear-${sms.id}`"></div>
              </template>
            </div>
          </div>
        </div>
        <div
          class="kt-portlet__foot"
          v-if="internetShop.phone_number.deleted_at == null || !to"
        >
          <div class="kt-chat__input">
            <div class="kt-chat__editor">
              <textarea
                placeholder="Type here..."
                style="height: 50px"
                v-model="replyContent"
              >
              </textarea>
              <v-select
                v-model="to"
                :items="numbers"
                label="Select the recipient"
              >
              </v-select>
            </div>
            <div class="kt-chat__toolbar">
              <div class="kt_chat__tools"></div>
              <div class="kt_chat__actions">
                <button
                  type="button"
                  class="btn btn-brand btn-md btn-font-sm btn-upper btn-bold kt-chat__reply"
                  @click="sendReply"
                  :loading="replyLoading"
                  :disabled="!internetShop.lead_phone_number || !to"
                  v-if="$auth.check()"
                >
                  reply
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <p v-else class="lead ma-3 pa-2">No SMS Logs Data Available</p>
  </v-card>
</template>
<script>
import { each as _each, some as _some } from 'lodash-es';
export default {
  name: 'SmsLogs',
  data: () => ({
    to: '',
    replyContent: '',
    replyLoading: false,
    pseudoSmsLogId: 999100,
    numbers: [],
    aws_url: '',
  }),
  created() {
    this.extractSendersPhoneNumbersList();
    this.$http.get('venv/aws-url').then((response) => {
      this.aws_url = response.data.aws_url;
    });
  },
  props: {
    shop: {
      required: false,
      type: Object,
    },
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
  methods: {
    extractSendersPhoneNumbersList() {
      this.numbers = [];
      _each(this.internetShop.phone_number.sms, (sms) => {
        if (!this.isOutbound(sms.from)) {
          this.numbers.push(sms.from);
        }
      });
    },
    isOutbound(fromNumber) {
      return (
        this.internetShop.phone_number.value == fromNumber ||
        this.internetShop.phone_number.formatted_value == fromNumber
      );
    },
    sendReply() {
      this.replyLoading = true;
      if (!this.replyContent) {
        this.$notify('error', 'Empty message is not allowed', true);
        return false;
      }

      const leadNumberExtracted = this.to.match(/\d/g);
      const leadNumberFormatted = '+1' + leadNumberExtracted.join('');
      this.$http
        .post('/twilio/reply/sms', {
          body: this.replyContent,
          from: this.internetShop.phone_number.value,
          to: leadNumberFormatted,
        })
        .then((response) => {
          this.$notify('success', 'Successfully sent message', true);
          this.shop.phone_number.sms.push({
            created_at: this.$moment().format('MM/DD/YYYY hh:mm a'),
            from: this.internetShop.phone_number.value,
            id: this.pseudoSmsLogId,
            phone_number_id: this.internetShop.phone_number.id,
            sms_message_sid: '1',
            start_at: this.$moment().format('MM/DD/YYYY hh:mm a'),
            start_at_formatted: this.$moment().format('MM/DD/YYYY hh:mm a'),
            to: this.to,
            updated_at: this.$moment().format('MM/DD/YYYY hh:mm a'),
            value: this.replyContent,
          });
          this.pseudoSmsLogId++;
        })
        .catch((error) => {
          this.$notify('error', error.response.data.message, true);
        })
        .finally(() => {
          this.replyLoading = false;
          this.replyContent = '';
          this.to = '';
        });
    },
    deleteSms(id, index) {
      console.log(id);
      this.$http
        .delete(`sms/${id}`)
        .then((response) => {
          this.internetShop.phone_number.sms.splice(index, 1);
          this.$notify('success', 'Successfully deleted the sms.');
        })
        .catch(() => {});
    },

    getExtension(filename) {
      let parts = filename.split('.');
      return parts[parts.length - 1];
    },

    isImage(filename) {
      let ext = this.getExtension(filename);
      switch (ext.toLowerCase()) {
        case 'jpg':
        case 'gif':
        case 'bmp':
        case 'png':
          //etc
          return true;
      }
      return false;
    },

    isVideo(filename) {
      let ext = this.getExtension(filename);
      switch (ext.toLowerCase()) {
        case 'm4v':
        case 'avi':
        case 'mpg':
        case 'mp4':
          // etc
          return true;
      }
      return false;
    },

    isAudio(filename) {
      let ext = this.getExtension(filename);
      switch (ext.toLowerCase()) {
        case 'mp3':
        case 'wav':
        case 'wma':
          // etc
          return true;
      }
      return false;
    },
  },
};
</script>
