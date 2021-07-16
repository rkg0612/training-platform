<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-store-alt"></i>
        </span>
        <h3 class="kt-portlet__head-title">Company Accounts</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
          <v-dialog
            v-model="newDealerForm"
            hide-overlay
            transition="dialog-bottom-transition"
          >
            <template v-slot:activator="{ on }">
              <v-btn color="blue darken-3" dark class="mb-2" v-on="on" text>
                <i class="fal fa-plus mr-2"></i>
                New Company
              </v-btn>
            </template>
            <v-card>
              <v-toolbar dark color="secondary">
                <v-card-title>
                  <i class="fal fa-store-alt mr-2"></i>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-spacer></v-spacer>
                <button
                  type="button"
                  class="btn btn-light btn-elevate-hover btn-circle btn-icon btn-sm"
                  @click="close"
                >
                  <i class="fal fa-times"></i>
                </button>
              </v-toolbar>
              <v-card-text>
                <v-container class="kt-form">
                  <!-- Start of Dealer's Info -->
                  <h4 class="mt-5">
                    <span>
                      <i class="fal fa-info-circle"></i>
                    </span>
                    Company's Info
                  </h4>
                  <v-row>
                    <v-col cols="12" sm="6" md="3">
                      <v-switch
                        v-model="editedItem.dealer.is_automotive"
                        label="Is Automotive Group?"
                        messages="This switch is very important for automotive
                        groups because it gives them more filters for their data."
                      ></v-switch>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                      <v-text-field
                        v-model="editedItem.dealer.name"
                        label="Company's Name"
                        v-validate="'required'"
                        name="dealer.name"
                      ></v-text-field>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('dealer.name')"
                        >{{ errors.first('dealer.name') }}</span
                      >
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                      <v-text-field
                        v-model="editedItem.dealer.website"
                        label="Dealer's Site"
                        v-validate="'required|url'"
                        name="dealer.website"
                      ></v-text-field>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('dealer.website')"
                        >{{ errors.first('dealer.website') }}</span
                      >
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                      <v-text-field
                        v-model="editedItem.dealer.address"
                        label="Dealer's Address"
                        v-validate="'required'"
                        name="dealer.address"
                      ></v-text-field>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('dealer.address')"
                        >{{ errors.first('dealer.address') }}</span
                      >
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-checkbox
                        v-model="editedItem.dealer.lms_service"
                        label="LMS Access"
                        value="allowed"
                      ></v-checkbox>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-checkbox
                        v-model="editedItem.dealer.secretshop_service"
                        label="Secretshop Access"
                        value="allowed"
                      ></v-checkbox>
                    </v-col>
                  </v-row>
                  <v-divider></v-divider>
                  <!-- End of Dealer's Info -->
                  <!-- Start of Layout Section -->
                  <h4 class="mt-5">
                    <span>
                      <i class="fal fa-cogs"></i>
                    </span>
                    Layout
                  </h4>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-file-input
                        v-model="files.primaryLogo"
                        label="Primary Logo"
                        messages="Advisable size is 400x150"
                      ></v-file-input>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-file-input
                        v-model="files.secondaryLogo"
                        label="Secondary Logo"
                        messages="Advisable size is 400x150"
                      ></v-file-input>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-file-input
                        v-model="files.homeBanner"
                        label="Home Page Banner"
                        messages="Advisable size is 3840x300"
                      ></v-file-input>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.options.lms_video_banner"
                        label="LMS Intro Video Link"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="12">
                      <div class="form-group">
                        <label>LMS Description</label>
                        <quill-editor
                          ref="lmsDescription"
                          v-model="editedItem.options.lms_description"
                          :options="editorOption"
                        ></quill-editor>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12" md="3">
                      <div class="form-group">
                        <label>Sidebar BG Color</label>
                        <v-color-picker
                          :mode.sync="mode"
                          v-model="editedItem.options.sidebar_bg_color"
                          hide-canvas
                        ></v-color-picker>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12" md="3">
                      <div class="form-group">
                        <label>Topbar BG Color</label>
                        <v-color-picker
                          :mode.sync="mode"
                          v-model="editedItem.options.header_bg_color"
                          hide-canvas
                        ></v-color-picker>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12" md="3">
                      <div class="form-group">
                        <label>Logo BG Color</label>
                        <v-color-picker
                          :mode.sync="mode"
                          v-model="editedItem.options.logo_bg_color"
                          hide-canvas
                        ></v-color-picker>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12" md="3">
                      <div class="form-group">
                        <label>Top Menu Hover Color</label>
                        <v-color-picker
                          :mode.sync="mode"
                          v-model="editedItem.options.top_menu_hover_color"
                          hide-canvas
                        ></v-color-picker>
                      </div>
                    </v-col>
                    <v-row>
                      <v-col cols="12" sm="12" md="3">
                        <div class="form-group">
                          <label>Sidebar Menu Hover Color</label>
                          <v-color-picker
                            :mode.sync="mode"
                            v-model="
                              editedItem.options.sidebar_menu_hover_color
                            "
                            hide-canvas
                          ></v-color-picker>
                        </div>
                      </v-col>
                      <v-col cols="12" sm="12" md="3">
                        <div class="form-group">
                          <label>Sidebar Font Color</label>
                          <v-color-picker
                            :mode.sync="mode"
                            v-model="editedItem.options.sidebar_font_color"
                            hide-canvas
                          ></v-color-picker>
                        </div>
                      </v-col>
                      <v-col cols="12" sm="12" md="3">
                        <div class="form-group">
                          <label>Topbar Font Color</label>
                          <v-color-picker
                            :mode.sync="mode"
                            v-model="editedItem.options.header_font_color"
                            hide-canvas
                          ></v-color-picker>
                        </div>
                      </v-col>
                      <v-col cols="12" sm="12" md="3">
                        <div class="form-group">
                          <label>Training Category Font Color</label>
                          <v-color-picker
                            :mode.sync="mode"
                            v-model="
                              editedItem.options.training_category_font_color
                            "
                            hide-canvas
                          ></v-color-picker>
                        </div>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" sm="12" md="3">
                        <v-switch
                          v-model="
                            editedItem.options.show_units_and_subsections
                          "
                          color="yellow darken-3"
                          label="Show Units and Sub-Sections"
                        ></v-switch>
                      </v-col>
                      <v-col cols="12" sm="12" md="3">
                        <v-switch
                          v-model="editedItem.options.show_category_slider"
                          color="yellow darken-3"
                          label="Show Category Slider"
                        ></v-switch>
                      </v-col>
                      <v-col cols="12" sm="12" md="3">
                        <v-switch
                          v-model="
                            editedItem.options.show_three_column_category
                          "
                          color="yellow darken-3"
                          label="Show 3 Column Category"
                        ></v-switch>
                      </v-col>
                      <v-col cols="12" sm="12" md="3">
                        <v-switch
                          v-model="editedItem.options.show_video_banner"
                          color="yellow darken-3"
                          label="Show Video Banner"
                        ></v-switch>
                      </v-col>
                    </v-row>
                  </v-row>
                  <v-divider></v-divider>
                  <!-- End of Layout Section -->
                  <!-- Start of Secret Shop Setting Section -->
                  <h4 class="mt-5">
                    <span>
                      <i class="fal fa-user-secret"></i>
                    </span>
                    Secret Shop Setting
                  </h4>
                  <h5>Email Condition</h5>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:30:00 above is red -->
                      <v-text-field
                        v-model="editedItem.options.email_red_response_time"
                        label="Red Response Time"
                        placeholder="00:30:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:06:00 to 00:29:00 is yellow -->
                      <v-text-field
                        v-model="editedItem.options.email_yellow_response_time"
                        label="Yellow Response Time"
                        placeholder="00:06:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:00:00 to 00:05:00 is green -->
                      <v-text-field
                        v-model="editedItem.options.email_green_response_time"
                        label="Green Response Time"
                        placeholder="00:00:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 1 or less than 1 -->
                      <v-text-field
                        v-model="editedItem.options.email_red_attempts"
                        label="Red Attempts"
                        placeholder="1"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 3 or less than 3 but greater than 1 -->
                      <v-text-field
                        v-model="editedItem.options.email_yellow_attempts"
                        label="Yellow Attempts"
                        placeholder="3"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 4 or greater than 4 -->
                      <v-text-field
                        v-model="editedItem.options.email_green_attempts"
                        label="Green Attempts"
                        placeholder="4"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <h5>Call Condition</h5>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:30:00 above is red -->
                      <v-text-field
                        v-model="editedItem.options.call_red_response_time"
                        label="Red"
                        placeholder="00:30:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:06:00 to 00:29:00 is yellow -->
                      <v-text-field
                        v-model="editedItem.options.call_yellow_response_time"
                        label="Yellow"
                        placeholder="00:06:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:00:00 to 00:05:00 is green -->
                      <v-text-field
                        v-model="editedItem.options.call_green_response_time"
                        label="Green"
                        placeholder="00:00:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 2 or less than 2 -->
                      <v-text-field
                        v-model="editedItem.options.call_red_attempts"
                        label="Red Attempts"
                        placeholder="2"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 5 or less than 5 but greater than 2 -->
                      <v-text-field
                        v-model="editedItem.options.call_yellow_attempts"
                        label="Yellow Attempts"
                        placeholder="5"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 6 or greater than 6 -->
                      <v-text-field
                        v-model="editedItem.options.call_green_attempts"
                        label="Green Attempts"
                        placeholder="6"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <h5>SMS Condition</h5>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:30:00 above is red -->
                      <v-text-field
                        v-model="editedItem.options.sms_red_response_time"
                        label="Red"
                        placeholder="00:30:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:06:00 to 00:29:00 is yellow -->
                      <v-text-field
                        v-model="editedItem.options.sms_yellow_response_time"
                        label="Yellow"
                        placeholder="00:06:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 00:00:00 to 00:05:00 is green -->
                      <v-text-field
                        v-model="editedItem.options.sms_green_response_time"
                        label="Green"
                        placeholder="00:00:00"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 2 or less than 2 -->
                      <v-text-field
                        v-model="editedItem.options.sms_red_attempts"
                        label="Red Attempts"
                        placeholder="2"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 5 or less than 5 but greater than 2 -->
                      <v-text-field
                        v-model="editedItem.options.sms_yellow_attempts"
                        label="Yellow Attempts"
                        placeholder="5"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <!-- Default 6 or greater than 6 -->
                      <v-text-field
                        v-model="editedItem.options.sms_green_attempts"
                        label="Green Attempts"
                        placeholder="6"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <!-- End of Secret Shop Setting Section -->
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-grey darken-1" text @click="close"
                  >Cancel</v-btn
                >
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="dealers"
        :search="dealerSearch"
        sort-by="dealers"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat color="white">
            <v-row>
              <v-spacer></v-spacer>
              <v-col cols="12" xl="4" class="text-right">
                <v-text-field
                  v-model="dealerSearch"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
              </v-col>
            </v-row>
          </v-toolbar>
        </template>
        <template v-slot:item.action="{ item }">
          <v-btn text @click="editItem(item)" color="grey darken-3" small>
            <v-icon small>fal fa-edit</v-icon>
          </v-btn>
          <v-btn text @click="deleteItem(item)" color="red darken-3" small>
            <v-icon small>fal fa-trash</v-icon>
          </v-btn>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import dayjs from 'dayjs';
import {
  each as lodashEach,
  head as lodashHead,
  assign as lodashAssign,
  isEmpty as _isEmpty,
} from 'lodash-es';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import { quillEditor } from 'vue-quill-editor';

export default {
  name: 'DealerBase',
  components: {
    quillEditor,
  },
  data: () => ({
    editorOption: {},
    dealerSearch: '',
    newDealerForm: false,
    showPassword: false,
    showConfirmPassword: false,
    mode: 'hexa',
    headers: [
      {
        text: 'Name of Company',
        align: 'left',
        sortable: true,
        value: 'dealer.name',
      },
      {
        text: 'Date Created',
        value: 'dealer.created_at',
      },
      {
        text: 'Address',
        value: 'dealer.address',
      },
      {
        text: 'Status',
        value: 'dealer.active_status_friendly_display',
      },
      {
        text: 'Actions',
        value: 'action',
        align: 'center',
        width: 200,
        sortable: false,
      },
    ],
    dealers: [],
    editedIndex: -1,
    files: {
      primaryLogo: null,
      secondaryLogo: null,
      homeBanner: null,
    },
    editedItem: {
      dealer: {
        name: '',
        website: '',
        address: '',
        is_automotive: '',
        lms_service: 0,
        secretshop_service: 0,
      },
      options: {
        lms_video_banner: '',
        lms_description: '',
        sidebar_bg_color: '#353535',
        header_bg_color: '#F9B418',
        logo_bg_color: '#222222',
        top_menu_hover_color: '#F1C40f',
        sidebar_menu_hover_color: '#555555',
        sidebar_font_color: '#A2A3B7',
        header_font_color: '#262626',
        training_category_font_color: '#F9B418',
        show_units_and_subsections: false,
        show_category_slider: false,
        show_three_column_category: false,
        show_video_banner: false,
        email_red_response_time: '00:30:00',
        email_yellow_response_time: '00:06:00',
        email_green_response_time: '00:00:00',
        email_red_attempts: 1,
        email_yellow_attempts: 3,
        email_green_attempts: 4,
        call_red_response_time: '00:30:00',
        call_yellow_response_time: '00:06:00',
        call_green_response_time: '00:00:00',
        call_red_attempts: 1,
        call_yellow_attempts: 3,
        call_green_attempts: 4,
        sms_red_response_time: '00:30:00',
        sms_yellow_response_time: '00:06:00',
        sms_green_response_time: '00:00:00',
        sms_red_attempts: '1',
        sms_yellow_attempts: '3',
        sms_green_attempts: '4',
      },
    },
    defaultItem: {
      primaryLogo: null,
      secondaryLogo: null,
      homeBanner: null,
      dealer: {
        name: '',
        website: '',
        address: '',
        is_automotive: false,
        lms_service: 0,
        secretshop_service: 0,
      },
      options: {
        lms_video_banner: '',
        lms_description: '',
        sidebar_bg_color: '#353535',
        header_bg_color: '#F9B418',
        logo_bg_color: '#222222',
        top_menu_hover_color: '#4d5995',
        sidebar_menu_hover_color: '#555555',
        sidebar_font_color: '#A2A3B7',
        header_font_color: '#262626',
        training_category_font_color: '#F9B418',
        show_units_and_subsections: false,
        show_category_slider: false,
        show_three_column_category: false,
        show_video_banner: false,
        email_red_response_time: '00:30:00',
        email_yellow_response_time: '00:06:00',
        email_green_response_time: '00:00:00',
        email_red_attempts: 1,
        email_yellow_attempts: 3,
        email_green_attempts: 4,
        call_red_response_time: '00:30:00',
        call_yellow_response_time: '00:06:00',
        call_green_response_time: '00:00:00',
        call_red_attempts: 1,
        call_yellow_attempts: 3,
        call_green_attempts: 4,
        sms_red_response_time: '00:30:00',
        sms_yellow_response_time: '00:06:00',
        sms_green_response_time: '00:00:00',
        sms_red_attempts: '1',
        sms_yellow_attempts: '3',
        sms_green_attempts: '4',
      },
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Dealer' : 'Edit Dealer';
    },
  },

  watch: {
    newDealerForm(val) {
      val || this.close();
    },
  },

  created() {
    this.initialize();
    this.$validator.localize('en', {
      attributes: {
        'dealer.address': 'Dealer Address',
        'dealer.name': 'Company Name',
        'dealer.website': 'Dealer Website',
      },
    });
  },

  methods: {
    initialize() {
      this.getDealers();
    },

    getDealers() {
      this.$http
        .get('/dealers')
        .then((response) => {
          lodashEach(response.data, (dealer) => {
            this.dealers.push({
              dealer: {
                id: dealer.id,
                name: dealer.name,
                website: dealer.website,
                address: dealer.address,
                is_automotive: dealer.is_automotive,
                lms_service: dealer.lms_service == 1 ? 'allowed' : 'disallowed',
                secretshop_service: dealer.secretshop_service
                  ? 'allowed'
                  : 'disallowed',
                created_at: dayjs(dealer.created_at).format(
                  'MM/DD/YYYY hh:mm A'
                ),
                is_active: dealer.is_active,
                active_status_friendly_display: dealer.is_active
                  ? 'Active'
                  : 'Inactive',
              },
              options: dealer.options,
            });
          });
        })
        .catch((e) => {
          console.log(e);
        });
    },

    editItem(item) {
      const options = {};
      lodashEach(item.options, (option) => {
        options[option.name] = option.value;
      });
      const dealerItem = { dealer: item.dealer, options };
      this.editedIndex = this.dealers
        .map((dealer) => dealer.dealer.id)
        .indexOf(item.dealer.id);
      this.editedItem = this.copyObjectDefault(dealerItem, this.defaultItem);
      this.newDealerForm = true;
    },
    copyObjectDefault(modified, templateObject) {
      for (const optionName in templateObject.options) {
        // eslint-disable-next-line no-prototype-builtins
        if (!modified.options.hasOwnProperty(optionName)) {
          // eslint-disable-next-line no-param-reassign
          modified.options[optionName] = templateObject.options[optionName];
        }
      }

      return modified;
    },
    deleteItem(item) {
      const index = this.dealers
        .map((dealer) => dealer.dealer.id)
        .indexOf(item.dealer.id);
      this.$swal({
        title: 'Are you sure?',
        text: "You can't revert this action.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it.',
        showCloseButton: true,
      }).then((result) => {
        if (result.value) {
          const deleteDealerUrl = `/dealers/${this.dealers[index].dealer.id}`;
          console.log(deleteDealerUrl);
          this.$http
            .delete(deleteDealerUrl)
            .then((response) => {
              this.dealers[index].dealer.is_active = !this.dealers[index].dealer
                .is_active;
              this.dealers[index].dealer.active_status_friendly_display = this
                .dealers[index].dealer.is_active
                ? 'Active'
                : 'Inactive';
              const dealerValue = this.dealers[index];
              this.$notify('success', 'Dealer successfully deleted.');
              this.dealers.splice(index, 1, dealerValue);
            })
            .catch(() => {
              this.$notify('danger', 'Error occured when deleting the dealer.');
            });
          // this.dealers.splice(index, 1);
        }
      });
    },

    close() {
      this.newDealerForm = false;
      this.$validator.reset();
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    updateOrSave() {
      this.editedItem.dealer.is_automotive =
        true == this.editedItem.dealer.is_automotive ? true : false;
      if (this.editedIndex > -1) {
        this.updateCompany();
      } else {
        this.newCompany();
      }
    },

    updateCompany() {
      this.editedItem.dealer.lms_service =
        this.editedItem.dealer.lms_service !== null &&
        this.editedItem.dealer.lms_service == 'allowed'
          ? 'allowed'
          : 'disallowed';
      this.editedItem.dealer.secretshop_service =
        this.editedItem.dealer.secretshop_service !== null &&
        this.editedItem.dealer.secretshop_service == 'allowed'
          ? 'allowed'
          : 'disallowed';
      this.$http
        .patch(`/dealers/${this.editedItem.dealer.id}`, this.editedItem)
        .then((response) => {
          const dealer = response.data;
          this.dealers.splice(this.editedIndex, 1, {
            dealer: {
              id: dealer.id,
              name: dealer.name,
              website: dealer.website,
              address: dealer.address,
              is_automotive: dealer.is_automotive,
              created_at: dayjs(dealer.created_at).format('MM/DD/YYYY hh:mm A'),
              is_active: dealer.is_active,
              lms_service: dealer.lms_service ? 'allowed' : 'disallowed',
              secretshop_service: dealer.secretshop_service
                ? 'allowed'
                : 'disallowed',
              active_status_friendly_display: dealer.is_active
                ? 'Active'
                : 'Inactive',
            },
            options: dealer.options,
          });
          this.$notify('success', 'Dealer updated successfully.');
          this.uploadFiles(this.editedItem.dealer.id);
        })
        .catch((errors) => {
          const messages = errors.response.data.errors;
          lodashEach(messages, (value, key) => {
            this.errors.add({
              field: key,
              msg: lodashHead(value),
            });
          });
        });
    },

    newCompany() {
      this.editedItem.dealer.lms_service =
        this.editedItem.dealer.lms_service !== null &&
        this.editedItem.dealer.lms_service == 'allowed'
          ? 'allowed'
          : 'disallowed';
      this.editedItem.dealer.secretshop_service =
        this.editedItem.dealer.secretshop_service !== null &&
        this.editedItem.dealer.secretshop_service == 'allowed'
          ? 'allowed'
          : 'disallowed';
      this.$http
        .post('/dealers/', this.editedItem)
        .then((response) => {
          const dealer = response.data;
          this.dealers.push({
            dealer: {
              id: dealer.id,
              name: dealer.name,
              website: dealer.website,
              address: dealer.address,
              is_automotive: dealer.is_automotive,
              created_at: dayjs(dealer.created_at).format('MM/DD/YYYY hh:mm A'),
              is_active: dealer.is_active,
              lms_service: dealer.lms_service ? 'allowed' : 'disallowed',
              secretshop_service: dealer.secretshop_service
                ? 'allowed'
                : 'disallowed',
              active_status_friendly_display: dealer.is_active
                ? 'Active'
                : 'Inactive',
            },
            options: dealer.options,
          });
          this.$notify('success', 'New dealer successfully created.');
          this.uploadFiles(dealer.id);
        })
        .catch((errors) => {
          const messages = errors.response.data.errors;
          lodashEach(messages, (value, key) => {
            this.errors.add({
              field: key,
              msg: lodashHead(value),
            });
          });
        });
    },

    uploadFiles(dealerId) {
      const formImages = new FormData();

      formImages.append('dealer_id', dealerId);
      if (!_isEmpty(this.files.primaryLogo)) {
        formImages.append('logo_image', this.files.primaryLogo);
      }
      if (!_isEmpty(this.files.secondaryLogo)) {
        formImages.append('secondary_logo', this.files.secondaryLogo);
      }
      if (!_isEmpty(this.files.homeBanner)) {
        formImages.append('background_image', this.files.homeBanner);
      }

      if (
        !_isEmpty(this.files.homeBanner) ||
        !_isEmpty(this.files.secondaryLogo) ||
        !_isEmpty(this.files.primaryLogo)
      ) {
        this.axios
          .post('dealers/settings/file-uploads', formImages, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          .finally(() => {
            this.close();
          });
      }
    },

    save() {
      this.$validator.validate('*').then((result) => {
        if (result) {
          this.updateOrSave();
        }
      });
    },
  },
};
</script>
œ
