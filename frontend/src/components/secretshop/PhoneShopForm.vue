<template>
  <v-dialog v-model="show" hide-overlay transition="dialog-bottom-transition">
    <v-card>
      <v-toolbar dark color="secondary">
        <v-card-title>
          <i class="fal fa-phone"></i>
          <span class="headline ml-2">
            {{ formTitle }}
          </span>
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
          <!-- Start of Phone Shop -->
          <h5>Dealer's Info</h5>
          <v-row>
            <v-col cols="12" sm="12" md="6">
              <label for="dealer">Dealer</label>
              <vue-select
                id="dealer"
                v-model="editedItem.dealer"
                :options="dealers"
                label="name"
                v-validate="'required'"
                :reduce="(dealer) => dealer.id"
                @input="editedItem.specific_dealer = null"
                name="dealer"
              >
              </vue-select>
              <span class="kt-font-danger validate-error">
                {{ errors.first('dealer') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <label for="specific_dealer">Specific Dealer</label>
              <vue-select
                id="specific_dealer"
                v-model="editedItem.specific_dealer"
                :options="specificDealers"
                name="specific_dealer"
                v-validate="'required'"
                :reduce="(specificDealer) => specificDealer.name"
                label="name"
                taggable
                :create-option="(specificDealer) => ({ name: specificDealer })"
              >
              </vue-select>
              <span class="kt-font-danger validate-error">
                {{ errors.first('specific_dealer') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <label for="state">State</label>
              <vue-select
                id="state"
                v-model="editedItem.state"
                :options="states"
                :reduce="(state) => state.id"
                v-validate="'required'"
                @input="getCities"
                label="name"
                name="state"
                taggable
              ></vue-select>
              <span class="kt-font-danger validate-error">
                {{ errors.first('state') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <label for="city">City</label>
              <vue-select
                id="city"
                v-model="editedItem.city"
                label="value"
                :options="cities"
                :reduce="(city) => city.value"
                v-validate="'required'"
                name="city"
                taggable
                :create-option="(city) => ({ value: city })"
              >
              </vue-select>
              <span class="kt-font-danger validate-error">
                {{ errors.first('city') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <label for="zip_code">Zip Code</label>
              <v-text-field
                id="zip_code"
                v-model="editedItem.zip_code"
                v-validate="'required'"
                name="zip_code"
              >
              </v-text-field>
              <span class="kt-font-danger validate-error">
                {{ errors.first('zip_code') }}
              </span>
            </v-col>
          </v-row>
          <h5 class="mt-5">Shop's Info</h5>
          <v-row>
            <v-col cols="12" sm="12" md="3">
              <v-radio-group
                v-model="editedItem.shop_type"
                row
                v-validate="'required'"
                name="shop_type"
              >
                <v-radio label="New" value="1" color="yellow darken-3">
                </v-radio>
                <v-radio label="Used" value="0" color="yellow darken-3">
                </v-radio>
              </v-radio-group>
              <span class="kt-font-danger validate-error">
                {{ errors.first('shop_type') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="3">
              <v-text-field
                v-model="editedItem.sales_person_name"
                label="Salesperson's Name"
                name="sales_person_name"
                v-validate="'required'"
              >
              </v-text-field>
              <span class="kt-font-danger validate-error">
                {{ errors.first('sales_person_name') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="3">
              <v-text-field
                v-model="editedItem.lead_name"
                label="Lead's Name"
                name="lead_name"
                v-validate="'required'"
              >
              </v-text-field>
              <span class="kt-font-danger validate-error">
                {{ errors.first('lead_name') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="3">
              <v-datetime-picker
                label="Shop's Date and Time"
                v-model="editedItem.start_date"
                dateIcon="fal fa-calendar-alt"
                timeIcon="fal fa-clock"
                date-format="MM-dd-yyyy"
                time-format="hh:mm a"
                name="start_date"
                v-validate="'required'"
              >
                <template slot="dateIcon">
                  <i class="fal fa-calendar-alt"></i>
                </template>
                <template slot="timeIcon">
                  <i class="fal fa-clock"></i>
                </template>
              </v-datetime-picker>
              <span class="kt-font-danger validate-error">
                {{ errors.first('start_date') }}
              </span>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="12" lg="6" xl="6">
              <label>Secret Shopper</label>
              <v-select
                v-validate="'required'"
                name="secret_shopper_id"
                v-model="editedItem.secret_shopper_id"
                :items="secretShoppers"
                label="Secret Shopper"
                item-text="name"
                item-value="id"
              ></v-select>
              <span class="kt-font-danger validate-error">
                {{ errors.first('secret_shopper_id') }}
              </span>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <display-audio
                :removed-files="removedFiles.dealer"
                type-of-recordings="dealerRecordings"
              >
              </display-audio>
              <v-file-input
                v-model="dealerRecordings"
                prepend-icon="fas fa-file-audio"
                label="Dealer Recordings"
                multiple
                accept="audio/*"
                placeholder="Upload Dealer Recordings"
              ></v-file-input>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <display-audio
                :removed-files="removedFiles.competitor"
                type-of-recordings="competitorRecordings"
              >
              </display-audio>
              <v-file-input
                v-model="competitorRecordings"
                prepend-icon="fas fa-file-audio"
                multiple
                accept="audio/*"
                label="Competitor Recordings"
                placeholder="Upload Competitor Recordings"
              ></v-file-input>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <h5>Inbound Call Grade</h5>
              <jodit-vue
                :getMyData="editedItem.inbound_call_grade"
                v-model="editedItem.inbound_call_grade"
                :extra-buttons="customButtons"
              >
              </jodit-vue>
              <span
                class="kt-font-danger validate-error"
                v-if="editedItem.inbound_call_grade === ''"
              >
                The inbound call grade is required.
              </span>
            </v-col>
          </v-row>
          <v-row>
            <v-spacer></v-spacer>
            <v-col>
              <div class="float-right">
                <v-btn color="blue-grey darken-1" text @click="close">
                  Cancel
                </v-btn>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="save"
                  :loading="loading.saving"
                >
                  Save
                </v-btn>
              </div>
            </v-col>
          </v-row>
          <!-- End of Phone Shop -->
        </v-container>
      </v-card-text>
    </v-card>
    <v-dialog v-model="showCreateTable" max-width="350">
      <v-card>
        <v-toolbar dark color="secondary">
          <v-card-title>
            <i class="fal fa-phone"></i>
            <span class="headline ml-2"> Create Inbound Call Grade Table </span>
          </v-card-title>
        </v-toolbar>
        <v-card-text>
          <v-row>
            <v-col
              ><v-text-field v-model="column" type="number"></v-text-field
            ></v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-btn @click="createTable">Create Table</v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-dialog>
</template>

<script>
import { secretShopper } from '../../plugins/templates';
import {
  filter as _filter,
  isEmpty as _isEmpty,
  forEach as _forEach,
  size as _size,
  orderBy as _orderBy,
  map as _map,
} from 'lodash-es';
import DisplayAudio from './DisplayAudio';
import dayjs from 'dayjs';

export default {
  name: 'PhoneShopForm',
  props: {
    value: {
      default: false,
    },
    phoneShop: {
      type: Object,
    },
    fetchPhoneShopRecords: {
      type: Function,
    },
  },
  components: {
    DisplayAudio,
  },
  data() {
    return {
      showCreateTable: false,
      column: 0,
      customButtons: [
        {
          name: 'webinarTable',
          iconURL:
            'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/home-banner.jpg',
          exec: () => {
            this.showCreateTable = true;
          },
        },
      ],
      editedItem: {
        dealer: '',
        specific_dealer: '',
        state: '',
        city: '',
        shop_type: '',
        zip_code: '',
        sales_person_name: '',
        lead_name: '',
        start_date: '',
        inbound_call_grade: '',
        secret_shopper_id: '',
      },
      temporaryRecordings: {
        competitorRecordings: [],
        dealerRecordings: [],
      },
      defaultItem: {
        dealer: '',
        specific_dealer: '',
        state: '',
        city: '',
        shop_type: '',
        zip_code: '',
        sales_person_name: '',
        lead_name: '',
        start_date: null,
        inbound_call_grade: secretShopper,
        secret_shopper_id: '',
      },
      loading: {
        saving: false,
        fetchSecretShoppers: false,
      },
      dealerRecordings: [],
      competitorRecordings: [],
      removedFiles: {
        dealer: [],
        competitor: [],
      },
      cities: [],
      secretShoppers: [],
    };
  },
  computed: {
    show: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
    formTitle() {
      if (!_isEmpty(this.phoneShop)) {
        return 'Edit PhoneShop';
      }
      return 'New PhoneShop';
    },
    dealers() {
      return _orderBy(this.$store.state.dealers.dealers, ['name'], 'asc');
    },
    specificDealers() {
      if (this.editedItem.dealer) {
        if (typeof this.editedItem.dealer === 'number') {
          return _filter(this.$store.state.specificDealers.list, {
            dealer_id: this.editedItem.dealer,
          });
        }
        return _filter(this.$store.state.specificDealers.list, {
          dealer_id: this.editedItem.dealer.id,
        });
      }

      return this.$store.state.specificDealers.list;
    },
    states() {
      return this.$store.state.states.states;
    },
  },
  watch: {
    show: {
      immediate: true,
      handler: function (val) {
        if (val && _isEmpty(this.phoneShop)) {
          this.editedItem.inbound_call_grade = secretShopper(2);
        }
        if (!val) {
          this.close();
        }
        if (val && !_isEmpty(this.phoneShop)) {
          this.prepEditData();
          this.setRecordings();
        }
      },
    },
    dealerRecordings: {
      immediate: true,
      deep: true,
      handler: function (val) {
        const recordings = _map(val, (recording) => {
          return {
            file: URL.createObjectURL(recording),
            name: recording.name,
          };
        });
        this.$store.dispatch('phoneShops/setRecordings', {
          recordings,
          type: 'dealer',
        });
      },
    },
    competitorRecordings: {
      immediate: true,
      deep: true,
      handler: function (val) {
        const recordings = _map(val, (recording) => {
          return {
            file: URL.createObjectURL(recording),
            name: recording.name,
          };
        });
        this.$store.dispatch('phoneShops/setRecordings', {
          recordings,
          type: 'competitor',
        });
      },
    },
  },
  created() {
    this.$validator.localize('en', {
      attributes: {
        specific_dealer: 'specific dealer',
        shop_type: 'shop type',
        sales_person_name: 'sales person name',
        lead_name: 'lead name',
        start_date: 'start date and time',
        zip_code: 'zip code',
        secret_shopper_id: 'secret shopper',
      },
    });
    this.getSecretShoppers();
  },
  methods: {
    createTable() {
      this.editedItem.inbound_call_grade = secretShopper(this.column);
      this.showCreateTable = false;
    },
    getSecretShoppers() {
      this.loading.fetchSecretShoppers = true;
      this.$http.get('/users/secret-shoppers').then(({ data }) => {
        this.secretShoppers = data;
        this.loading.fetchSecretShoppers = false;
      });
    },
    getCities(option) {
      this.editedItem.city = null;
      this.$http.get(`/cities/${option}`).then(({ data }) => {
        this.cities = data;
      });
    },
    setRecordings() {
      const { dealer_recordings, competitor_recordings } = this.phoneShop;
      const dealerRecordings = _map(dealer_recordings, (recording) => {
        return {
          file: recording.mp3,
          name: recording.label,
        };
      });
      this.$store.dispatch('phoneShops/setRecordings', {
        recordings: dealerRecordings,
        type: 'dealer',
      });
      const competitorRecordings = _map(competitor_recordings, (recording) => {
        return {
          file: recording.mp3,
          name: recording.label,
        };
      });
      this.$store.dispatch('phoneShops/setRecordings', {
        recordings: competitorRecordings,
        type: 'competitor',
      });
    },
    formatDate(date) {
      if (date) {
        return new dayjs(date).format('MM/DD/YYYY hh:mm a');
      }
      return null;
    },
    prepEditData() {
      this.editedItem.dealer = this.phoneShop.dealer;
      this.editedItem.specific_dealer = this.phoneShop.specific_dealer.name;
      this.editedItem.state = this.phoneShop.state.id;
      this.editedItem.city = this.phoneShop.city.value;
      this.editedItem.zip_code = this.phoneShop.zip_code;
      this.editedItem.shop_type = String(this.phoneShop.shop_type);
      this.editedItem.sales_person_name = this.phoneShop.sales_person_name;
      this.editedItem.lead_name = this.phoneShop.lead_name;
      this.editedItem.start_date = new Date(this.phoneShop.start_date);
      this.editedItem.inbound_call_grade = this.phoneShop.inbound_call_grade;
      this.editedItem.secret_shopper_id = this.phoneShop.secret_shopper.id;
    },
    checkIfObject(item, mustBeString = false) {
      if (item instanceof Object && !mustBeString) {
        return item.id;
      }
      if (item instanceof Object && mustBeString) {
        return item.name;
      }
      return item;
    },
    setFormData() {
      const form = new FormData();
      form.append('dealer_id', this.checkIfObject(this.editedItem.dealer));
      form.append(
        'specific_dealer_id',
        this.checkIfObject(this.editedItem.specific_dealer)
      );
      form.append('state_id', this.checkIfObject(this.editedItem.state));
      form.append('city', this.editedItem.city);
      form.append('shop_type', this.editedItem.shop_type);
      form.append('sales_person_name', this.editedItem.sales_person_name);
      form.append('start_date', this.formatDate(this.editedItem.start_date));
      form.append('zip_code', this.editedItem.zip_code);
      form.append('inbound_call_grade', this.editedItem.inbound_call_grade);
      form.append('lead_name', this.editedItem.lead_name);
      form.append('secret_shopper_id', this.editedItem.secret_shopper_id);
      _forEach(this.dealerRecordings, (dealerRecording) => {
        form.append('dealerRecordings[]', dealerRecording);
      });
      _forEach(this.competitorRecordings, (competitorRecording) => {
        form.append('competitorRecordings[]', competitorRecording);
      });
      if (!_isEmpty(this.removedFiles.dealer)) {
        _forEach(this.removedFiles.dealer, (dealer) => {
          form.append('dealerRecordingsRemoved[]', dealer.name);
        });
      }
      if (!_isEmpty(this.removedFiles.competitor)) {
        _forEach(this.removedFiles.competitor, (competitor) => {
          form.append('competitorRecordingsRemoved[]', competitor.name);
        });
      }
      return form;
    },

    close() {
      this.show = false;
      this.$validator.reset();
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedItem.start_date = '';
      this.removedFiles = {
        dealer: [],
        competitor: [],
      };
      this.$store.dispatch('phoneShops/resetRecordings');
    },

    store() {
      const form = this.setFormData();
      this.loading.saving = true;
      this.axios
        .post('/secret-shop-management/phone-shops', form, {
          headers: {
            'Content-type': 'multipart/form-data',
          },
        })
        .then(({ data }) => {
          this.$notify('success', 'Phone Shop Record added!');
          this.fetchPhoneShopRecords();
          this.close();
        })
        .finally(() => {
          this.loading.saving = false;
        });
    },

    checkIfNull(field) {
      return _size(_filter(field, { active: true })) >= 1;
    },

    update() {
      const form = this.setFormData();
      form.append('_method', 'PUT');
      form.append('id', this.phoneShop.id);
      this.loading.saving = true;
      this.$store
        .dispatch('phoneShops/updatePhoneShop', {
          id: this.phoneShop.id,
          payload: form,
        })
        .then(({ data }) => {
          this.$notify('success', 'Phone Shop Record updated!');
          this.fetchPhoneShopRecords();
          this.close();
        })
        .finally(() => {
          this.loading.saving = false;
        });
    },

    save() {
      this.$validator.validate('*').then((result) => {
        if (_isEmpty(this.phoneShop) && result) {
          return this.store();
        }
        if (result) {
          return this.update();
        }
      });
    },
  },
};
</script>

<style scoped></style>
