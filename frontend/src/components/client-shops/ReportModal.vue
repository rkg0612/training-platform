<template>
  <v-dialog v-model="showReportModal" :scrollable="true" max-width="900px ">
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
                append-icon
                label="To"
                clearable
                v-validate="'required'"
                name="to"
                :disabled="loading.sendReport"
                :delimiters="[' ', ',']"
              >
              </v-combobox>
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
              <component-loader :active="loading.preview"></component-loader>
              <jodit-vue
                v-model="report"
                v-validate="'required'"
                name="content"
                v-show="!loading.preview"
                :disabled="loading.sendReport"
              ></jodit-vue>
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
        <v-btn
          color="primary"
          @click="sendReport"
          :loading="loading.sendReport"
          :disabled="loading.sendReport"
        >
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
import { forEach as _forEach, head as _head, some as _some } from 'lodash-es';

export default {
  name: 'ReportModal',

  props: {
    value: Boolean,
  },

  data() {
    return {
      to: '',
      from: '',
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

  watch: {
    value: {
      immediate: true,
      handler: function (val) {
        if (val) {
          this.initialize();
        } else {
          this.resetForm();
        }
      },
    },
  },

  methods: {
    initialize() {
      if (this.$route.name === 'PhoneShop') {
        this.loading.preview = true;
        this.axios
          .get(`/preview-reports/phone-shop/${this.$route.params.id}`)
          .then(({ data }) => {
            this.report = data;
            this.loading.preview = false;
          })
          .finally(() => {
            this.loading.preview = false;
          });
      }

      if (this.$route.name === 'InternetShop') {
        this.loading.preview = true;
        this.axios
          .get(`/preview-reports/internet-shop/${this.$route.params.id}`)
          .then(({ data }) => {
            this.report = data;
            this.loading.preview = false;
          })
          .finally(() => {
            this.loading.preview = false;
          });
      }
    },
    sendReport() {
      this.loading.sendReport = true;
      this.$validator
        .validate('*')
        .then((result) => {
          if (result) {
            if (this.$route.name === 'PhoneShop') {
              this.phoneShopReport();
            }

            if (this.$route.name === 'InternetShop') {
              this.internetShopReport();
            }
          }
        })
        .finally(() => {
          setTimeout(() => {
            this.loading.sendReport = false;
            this.showReportModal = false;
          }, 1500);
        });
    },
    phoneShopReport() {
      this.$store
        .dispatch('phoneShopReport/sendReport', {
          content: this.report,
          to: this.to,
          from: this.from,
          subject: this.subject,
        })
        .then(({ data }) => {
          if (data === 'success') {
            this.$notify('success', 'Report sent!');

            setTimeout(() => {
              this.resetForm();
              this.showReportModal = false;
            }, 500);
          }
        })
        .catch((errors) => {
          this.interactWithErrorBag(errors, this.errors);
          this.loading.sendReport = false;
        })
        .finally(() => {
          this.loading.sendReport = false;
        });
    },
    internetShopReport() {
      this.$store
        .dispatch('internetShopReport/sendReport', {
          content: this.report,
          to: this.to,
          from: this.isWebinarincStaff ? this.from : 'admin@webinarinc.com',
          subject: this.subject,
        })
        .then(({ data }) => {
          if (data === 'success') {
            return this.$notify('success', 'Report sent!');
          }
          setTimeout(() => {
            this.resetForm();
            this.showReportModal = false;
          }, 500);
        })
        .catch((errors) => {
          this.interactWithErrorBag(errors, this.errors);
          this.loading.sendReport = false;
        })
        .finally(() => {
          this.loading.sendReport = false;
        });
    },
    resetForm() {
      this.to = '';
      this.from = '';
      this.subject = '';
      this.report = '';
    },
  },
};
</script>

<style lang="stylus" scoped>
[name='content']
  display:none;
</style>
