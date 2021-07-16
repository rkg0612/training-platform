<template xmlns:v-slot="http://www.w3.org/1999/html">
  <div class="kt-portlet kt-portlet--height-fluid mt-3">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm flex-wrap"
    >
      <div class="kt-portlet__head-label mb-2">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-phone-square-alt" />
        </span>
        <h3 class="kt-portlet__head-title">Phone Number Manager</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
          <v-btn
            color="deep-orange darken-3"
            dark
            class="mb-2 mr-2"
            v-on="on"
            @click="massDelete"
            text
          >
            <i class="fal fa-trash mr-2" />
            Release Multiple Number
          </v-btn>
          <v-dialog
            v-model="newNumberForm"
            max-width="800px"
            transition="dialog-bottom-transition"
          >
            <template v-slot:activator="{ on }">
              <v-btn color="blue darken-3" dark class="mb-2" v-on="on" text>
                <i class="fal fa-plus mr-2" />
                New Number
              </v-btn>
            </template>
            <v-card>
              <v-toolbar dark color="secondary">
                <v-card-title>
                  <i class="fal fa-phone-plus mr-1" />
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-spacer></v-spacer>
                <button
                  type="button"
                  class="btn btn-light btn-elevate-hover btn-circle btn-icon btn-sm"
                  @click="close"
                >
                  <i class="fal fa-times" />
                </button>
              </v-toolbar>
              <v-card-text>
                <v-container class="kt-form">
                  <!-- Number's Info -->
                  <h4 class="mt-5">
                    <span>
                      <i class="fal fa-info-circle" />
                    </span>
                    Number's Info
                  </h4>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.country"
                        disabled
                        label="Country"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-autocomplete
                        v-model="editedItem.state"
                        :items="states"
                        item-text="name"
                        item-value="id"
                        @change="setAreaCode"
                        v-validate="'required'"
                        name="state"
                        :menu-props="{ closeOnClick: true }"
                        label="State"
                        :return-object="false"
                        prepend-icon="fal fa-route-interstate"
                        autocomplete="new-password"
                      ></v-autocomplete>
                      <span class="kt-font-danger validate-error">{{
                        errors.first('state')
                      }}</span>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.area_code"
                        @blur="getNumbers"
                        v-validate="'required'"
                        label="Area Code"
                        :disabled="isFetchingNumbers"
                        name="areaCode"
                        append-icon="fal fa-search"
                      ></v-text-field>
                      <span class="kt-font-danger validate-error">{{
                        errors.first('areaCode')
                      }}</span>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-combobox
                        v-model="editedItem.number"
                        :items="numberList"
                        :menu-props="{ closeOnClick: true }"
                        label="Select a Number"
                        :loading="isNumberFetched"
                        v-validate="'required'"
                        name="number"
                        autocomplete="new-password"
                      />
                      <span class="kt-font-danger validate-error">{{
                        errors.first('number')
                      }}</span>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-autocomplete
                        v-model="editedItem.dealer"
                        v-validate="'required'"
                        name="dealers"
                        :items="dealers"
                        item-text="name"
                        item-value="id"
                        :menu-props="{ closeOnClick: true }"
                        :return-object="false"
                        label="Select a Company"
                        autocomplete="new-password"
                      ></v-autocomplete>
                      <span class="kt-font-danger validate-error">{{
                        errors.first('dealers')
                      }}</span>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-autocomplete
                        v-model="editedItem.secret_shopper"
                        :items="secretShopperList"
                        item-text="name"
                        item-value="id"
                        :return-object="false"
                        name="secretShopper"
                        v-validate="'required'"
                        :menu-props="{ closeOnClick: true }"
                        label="Select a Secret Shopper"
                        autocomplete="new-password"
                      />
                      <span class="kt-font-danger validate-error">{{
                        errors.first('secretShopper')
                      }}</span>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-select
                        v-model="editedItem.audio"
                        :items="voiceMails"
                        item-text="name"
                        item-value="id"
                        v-validate="'required'"
                        :return-object="false"
                        name="voiceMail"
                        :menu-props="{ closeOnClick: true }"
                        label="Select a Voicemail Recording"
                        autocomplete="new-password"
                      />
                      <span class="kt-font-danger validate-error">{{
                        errors.first('voiceMail')
                      }}</span>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-select
                        v-model="editedItem.category"
                        :items="categoryList"
                        :menu-props="{ closeOnClick: true }"
                        label="Category"
                        item-text="name"
                        item-value="value"
                        name="category"
                        :return-object="false"
                        v-validate="'required'"
                        autocomplete="new-password"
                      />
                      <span class="kt-font-danger validate-error">{{
                        errors.first('category')
                      }}</span>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer />
                <v-btn color="blue-grey darken-1" text @click="close"
                  >Cancel</v-btn
                >
                <v-btn
                  color="blue darken-1"
                  text
                  :loading="isLoading"
                  @click="save"
                  >Save</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="numbers.data"
        :search="pagination.search"
        :sort-by="pagination.sortBy"
        :sort-desc="sortDesc"
        class="elevation-1"
        :items-per-page="pagination.per_page"
        :server-items-length="pagination.total"
        @update:page="changePage"
        :loading="isFetchingRecords"
        :options.sync="options"
        v-model="selectedNumbers"
        show-select
      >
        <template v-slot:item.created_at="{ item }">
          <span>{{ item.created_at | displayDateTime }}</span>
        </template>
        <template v-slot:top>
          <div class="px-3">
            <div class="row no-gutters pt-3">
              <div class="col-xl-4">
                <v-select
                  class="mt-0 pt-0"
                  :loading="isFetchingRecords"
                  :disabled="isFetchingRecords"
                  @change="fetchData"
                  v-model="filters"
                  multiple
                  prepend-inner-icon="fas fa-filter"
                  :items="['Active', 'Inactive']"
                >
                  <template v-slot:prepend-item>
                    <v-list-item ripple @click="toggleAllFilter">
                      <v-list-item-content>
                        <v-list-item-title>All</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider class="mt-2"></v-divider>
                  </template>
                </v-select>
              </div>
              <v-spacer />
              <div class="col-xl-4">
                <v-text-field
                  class="mt-0 pt-0"
                  v-model.trim="pagination.search"
                  @change="fetchData"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
              </div>
            </div>
          </div>
        </template>
        <template v-slot:item.action="{ item }">
          <div v-if="item.deleted_at === null">
            <v-btn text @click="editItem(item)" color="grey darken-3" small>
              <v-icon small>fal fa-edit</v-icon>
            </v-btn>
            <v-btn text @click="deleteItem(item)" color="red darken-3" small>
              <v-icon small>fal fa-trash</v-icon>
            </v-btn>
          </div>
          <div v-else>INACTIVE RECORD</div>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="fetchData">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import {
  isEmpty as _isEmpty,
  forEach as _forEach,
  find as _find,
  head as _head,
} from 'lodash-es';

const dict = {
  custom: {
    areaCode: {
      required: 'The area code is required',
    },
    secretShopper: {
      required: 'The Secret Shopper field is required.',
    },
    voiceMail: {
      required: 'The voicemail field is required.',
    },
  },
};

export default {
  name: 'NumberManagement',
  data: () => ({
    options: {},
    isFetchingRecords: false,
    isLoading: false,
    isNumberFetched: false,
    isFetchingNumbers: false,
    on: '',
    newNumberForm: false,
    selectedNumbers: [],
    numberList: [],
    filters: '',
    categoryList: [
      {
        name: 'For Competitor',
        value: 0,
      },
      {
        name: 'For Dealer',
        value: 1,
      },
    ],
    headers: [
      {
        text: 'Number',
        align: 'left',
        sortable: false,
        value: 'value',
      },
      {
        text: 'Date Created',
        value: 'created_at',
        sortable: true,
      },
      {
        text: 'Secret Shopper',
        value: 'user.name',
        sortable: true,
      },
      {
        text: 'Company',
        value: 'dealer.name',
        sortable: true,
      },
      {
        text: 'Category',
        value: 'category_value',
        sortable: false,
      },
      {
        text: 'Actions',
        value: 'action',
        align: 'center',
        width: 200,
        sortable: false,
      },
    ],
    editedIndex: -1,
    editedItem: {
      country: 'US',
      state: '',
      area_code: '',
      number: '',
      secret_shopper: '',
      dealer: '',
      audio: '',
      category: '',
    },
    defaultItem: {
      country: 'US',
      state: '',
      area_code: '',
      number: '',
      secret_shopper: '',
      dealer: '',
      audio: '',
      category: '',
    },
    pagination: {
      total: 1,
      per_page: 5,
      current_page: 1,
      search: '',
      sortBy: 'created_at',
    },
    sortDesc: true,
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Number' : 'Edit Number';
    },
    states() {
      return this.$store.state.states.states;
    },
    cities() {
      return this.$store.state.cities.cities;
    },
    voiceMails() {
      return this.$store.state.voiceMails.voiceMails;
    },
    secretShopperList() {
      return this.$store.state.secretShoppers.secretShoppers;
    },
    dealers() {
      return this.$store.state.dealers.dealers;
    },
    numbers() {
      return this.$store.state.phoneNumbers.phoneNumbers;
    },
  },
  watch: {
    newNumberForm(val) {
      // eslint-disable-next-line no-unused-expressions
      val || this.close();
    },
    numbers(val) {
      this.pagination.total = val.total;
      this.pagination.current_page = val.current_page;
    },
    options: {
      handler() {
        this.fetchData();
      },
      deep: true,
    },
  },

  created() {
    this.toggleAllFilter();
    this.fetchData();
    this.$store.dispatch('secretShoppers/getSecretShoppers');
    this.$store.dispatch('voiceMails/getVoiceMails');
  },

  mounted() {
    this.$validator.localize('en', dict);
    this.init();
  },

  methods: {
    toggleAllFilter() {
      this.filters = ['Active', 'Inactive'];
    },
    init() {
      this.$store.dispatch('secretShoppers/getSecretShoppers');
      this.$store.dispatch('voiceMails/getVoiceMails');
    },
    changePage(page) {
      this.pagination.current_page = page;
      this.fetchData();
    },
    massDelete() {
      /* eslint-disable */
      let isSelectedNumbersEmpty = _isEmpty(this.selectedNumbers);
      if (!isSelectedNumbersEmpty) {
        let ids = [];
        _forEach(this.selectedNumbers, (val) => {
          ids.push(val.id);
        });
        this.$store
          .dispatch('phoneNumbers/deletePhoneNumber', ids)
          .then(() => {
            this.$notify('success', 'Phone Numbers removed.');
            this.$store.commit('phoneNumbers/massDeletePhoneNumber', ids);
          })
          .catch((error) => console.log(error));
      } else {
        this.$notify('error', 'There are no selected numbers!');
      }
    },
    fetchData() {
      this.pagination.sortBy =
        this.options.sortBy !== undefined
          ? this.options.sortBy[0]
          : 'created_at';
      this.pagination.sortDesc =
        this.options.sortDesc !== undefined ? this.options.sortDesc[0] : true;

      this.isFetchingRecords = true;
      this.$store
        .dispatch('phoneNumbers/fetchPhoneNumbers', {
          pagination: this.pagination,
          filters: this.filters,
        })
        .then(({ data }) => {
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
          this.$store.commit('phoneNumbers/setPhoneNumbers', data);
        })
        .catch((error) => {
          if (error.response.status === 404) {
            this.$notify(
              'error',
              'Please select one or more filter(s) for proper displaying of data.'
            );
            this.$store.commit('phoneNumbers/setPhoneNumbers', []);
          }
        })
        .finally(() => (this.isFetchingRecords = false));
    },
    setAreaCode(val) {
      const state = _find(this.states, { id: val });
      this.editedItem.area_code = state.area_codes;
    },
    store(payload) {
      this.isFetchingRecords = true;
      this.isLoading = true;
      this.$store
        .dispatch('phoneNumbers/addPhoneNumber', payload)
        .then(({ data }) => {
          this.fetchData();
          this.$notify('success', 'Added Phone Number');
        })
        .catch((error) => console.log(error))
        .finally(() => {
          this.isFetchingRecords = false;
          this.close();
        });
    },
    getNumbers() {
      const form = {
        areaCodes: this.editedItem.area_code.split(','),
      };
      this.$validator.validate('areaCode').then((valid) => {
        if (valid) {
          this.isNumberFetched = true;
          this.isFetchingNumbers = true;
          this.$store
            .dispatch('twilio/getAvailableNumbers', form)
            .then((response) => {
              this.numberList = response;
              if (this.numberList.length !== 0) {
                this.$notify(
                  'success',
                  'Successfully fetched numbers from twilio. You can now pick.'
                );
              } else {
                this.$notify(
                  'error',
                  'No number found matching the selected area number(s)'
                );
              }
            })
            .finally(() => {
              this.isNumberFetched = false;
              this.isFetchingRecords = false;
              this.isFetchingNumbers = false;
            });
        }
        return false;
      });
    },

    editItem(item) {
      this.editedIndex = this.numbers.data.indexOf(item);
      console.log(item);
      this.editedItem = {
        state: item.state_id !== null ? item.state.id : null,
        area_code: item.area_codes,
        number: item.value,
        secret_shopper: item.user.id,
        dealer: item.dealer.id,
        audio: item.voice_mail.id,
        category: item.is_dealer,
      };
      this.newNumberForm = true;
    },

    update(payload) {
      const index = this.editedIndex;
      const { id } = this.numbers.data[index];
      payload.id = id;
      this.isLoading = true;
      this.isFetchingRecords = true;
      this.$store
        .dispatch('phoneNumbers/updatePhoneNumber', payload)
        .then(({ data }) => {
          this.$notify('success', 'Phone number updated');
          this.$store.commit('phoneNumbers/editPhoneNumber', data);
        })
        .catch((error) => console.log(error))
        .finally(() => {
          this.isFetchingRecords = false;
          this.close();
        });
    },

    deleteItem(item) {
      const index = this.numbers.data.indexOf(item);
      const { id } = this.numbers.data[index];
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
          this.isFetchingRecords = true;
          this.$store
            .dispatch('phoneNumbers/deletePhoneNumber', id)
            .then(() => {
              this.$notify('success', 'Phone number released');
              this.fetchData();
            })
            .catch((error) => console.log(error))
            .finally(() => (this.isFetchingRecords = false));
        }
      });
    },

    close() {
      this.isLoading = false;
      this.newNumberForm = false;
      this.isFetchingNumbers = false;
      this.isFetchingRecords = false;
      this.isNumberFetched = false;
      this.numberList = [];
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
        this.$validator.reset();
      }, 300);
    },

    save() {
      this.$validator.localize('en', dict);
      this.$validator.validate('*').then((isValid) => {
        if (isValid) {
          // eslint-disable-next-line no-unused-expressions
          if (this.editedIndex === -1) {
            this.store(this.editedItem);
          } else {
            this.update(this.editedItem);
          }
        }
        this.$validator.reset();
      });
      this.$validator.reset();
    },
  },
};
</script>
<style lang="stylus" scoped>
.v-icon.v-icon {
    font-size: 16px;
}
</style>
