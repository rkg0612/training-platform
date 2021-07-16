<template>
  <v-dialog v-model="showReportModal" :scrollable="true" max-width="900px">
    <v-card>
      <v-card-title>
        <span class="headline">
          <v-icon class="mr-2">fal fa-paper-plane</v-icon>
          Send Report
        </span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-text-field
                label="Subject"
                v-model="subject"
                v-validate="'required'"
                name="subject"
                :disabled="loading.sendReport"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-show="errors.has('subject')"
              >
                {{ errors.first('subject') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <v-combobox
                multiple
                chips
                deletable-chips
                v-model="to"
                label="To"
                clearable
                v-validate="'required'"
                name="to"
                :disabled="loading.sendReport"
                :delimiters="[' ', ',']"
              />
              <span class="text-muted">
                Please press <code>Enter</code> after typing the email address.
              </span>
              <span
                class="kt-font-danger validate-error"
                v-show="errors.has('to')"
              >
                {{ errors.first('to') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <component-loader :active="loading.preview" />
              <jodit-vue
                v-model="report"
                v-validate="'required'"
                name="content"
                v-show="!loading.preview"
              />
              <span
                class="kt-font-danger validate-error"
                v-show="errors.has('content')"
              >
                {{ errors.first('content') }}
              </span>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" @click="send" :loading="loading.sendReport">
          Send
        </v-btn>
        <v-btn
          color="blue-grey"
          dark
          @click="showReportModal = false"
          :loading="loading.sendReport"
        >
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: 'GroupShopSendReportModal',

  props: {
    value: Boolean,
  },

  data() {
    return {
      to: '',
      subject: '',
      report: '',
      loading: {
        sendReport: false,
        preview: true,
      },
    };
  },

  computed: {
    showReportModal: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
  },

  methods: {
    getReportHtml() {
      const { id } = this.$route.params;

      this.$http.get(`/preview-reports/group-shop/${id}`).then(({ data }) => {
        this.loading.preview = false;
        this.report = data;
      });
    },
    send() {
      this.$validator.validate('*').then((result) => {
        if (result) {
          this.sendReport();
        }
      });
    },
    sendReport() {
      const { id } = this.$route.params;

      this.loading.sendReport = true;
      this.$http
        .post('/send-reports/group-shop', {
          id,
          to: this.to,
          subject: this.subject,
          report: this.report,
        })
        .then(() => {
          this.showReportModal = false;
          this.$notify('success', 'Report sent!');
        })
        .catch(() => {
          this.$notify('error', 'Encountered an error! Please try again.');
        })
        .finally(() => {
          this.loading.sendReport = false;
        });
    },
  },

  created() {
    this.getReportHtml();
  },
};
</script>

<style lang="stylus" scoped>
[name='content']
  display:none;
</style>
