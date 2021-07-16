<template>
  <v-dialog
    v-model="dialog"
    hide-overlay
    transition="dialog-bottom-transition"
    persistent
  >
    <v-card>
      <v-toolbar dark color="secondary">
        <v-card-title>
          <i class="fad fa-layer-group mr-2"></i>
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
      <v-card-text style="min-height: 600px">
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-text-field
                v-model="editedItem.name"
                label="Group Shop Name"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col v-show="secretShopper || administrator">
              <label for="dealer">Dealer</label>
              <vue-select
                id="dealer"
                v-model="editedItem.dealer_id"
                :options="dealers"
                label="name"
                v-validate="'required'"
                :reduce="(dealer) => dealer.id"
                name="dealer"
                @input="selectedDealer"
              />
            </v-col>
            <v-col
              v-show="
                (accountManager && isAutomotive) ||
                secretShopper ||
                administrator
              "
            >
              <label for="specific_dealer">Specific Dealer</label>
              <vue-select
                :disabled="isDealerEmpty"
                id="specific_dealer"
                v-model="editedItem.specific_dealer_id"
                :options="filteredSpecificDealers"
                name="specific_dealer"
                v-validate="'required'"
                :loading="loading.specificDealer"
                label="name"
                :reduce="(specificDealer) => specificDealer.id"
                taggable
              />
              <span class="kt-font-danger validate-error">{{
                errors.first('specific_dealer')
              }}</span>
            </v-col>
            <v-col cols="2">
              <v-checkbox
                label="Hide Dealer name"
                v-model="editedItem.hide_dealer_name"
                color="orange"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <label for="internet_shop">Internet Shop</label>
              <shop-select-dropdown
                shopType="internetShop"
                :value="editedItem.internet_shops"
                :setShopDropdownValue="setShopDropdownValue"
                :key="1"
              />
              <span class="kt-font-danger validate-error">
                {{ errors.first('internet_shop') }}
              </span>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <label for="phone_shop">Phone Shop</label>
              <shop-select-dropdown
                shopType="phoneShop"
                :value="editedItem.phone_shops"
                :setShopDropdownValue="setShopDropdownValue"
                :key="2"
              /> </v-col
          ></v-row>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue-grey darken-1" text @click="close">Cancel</v-btn>
        <v-btn
          color="blue darken-1"
          text
          @click="save"
          :loading="loading.request"
          >Save</v-btn
        >
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import {
  isEmpty as _isEmpty,
  orderBy as _orderBy,
  filter as _filter,
  cloneDeep as _cloneDeep,
  some as _some,
  forEach as _forEach,
  findIndex as _findIndex,
} from 'lodash-es';
import ShopSelectDropdown from '@/components/secretshop/GroupShop/ShopSelectDropdown';

export default {
  name: 'GroupShopForm',
  components: { ShopSelectDropdown },
  props: {
    value: {
      default: false,
      required: true,
      value: Boolean,
    },
    item: {
      default: {},
      required: false,
      value: Object,
    },
    getGroupShops: {
      type: Function,
      required: true,
    },
  },
  watch: {
    value(show) {
      if (show && !_isEmpty(this.item)) {
        this.setEditForm();
      }
    },
  },
  data() {
    return {
      specificDealers: [],
      isDealerEmpty: true,
      editedItem: {
        name: '',
        dealer_id: '',
        specific_dealer_id: '',
        internet_shops: [],
        phone_shops: [],
        hide_dealer_name: false,
      },
      search: {
        phoneShop: '',
        internetShops: '',
      },
      loading: {
        internetShop: false,
        phoneShop: false,
        dealer: false,
        specificDealer: false,
        request: false,
      },
      observer: {
        internetShop: null,
        phoneShop: null,
      },
      limit: {
        internetShop: 10,
        phoneShop: 10,
      },
      page: 1,
    };
  },
  computed: {
    dialog: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
    dealers() {
      return _orderBy(this.$store.state.dealers.dealers, ['name'], 'asc');
    },
    filteredSpecificDealers() {
      if (this.editedItem.dealer_id) {
        return _filter(this.specificDealers, {
          dealer_id: this.editedItem.dealer_id,
        });
      }

      return this.specificDealers;
    },
    internetShops() {
      return this.$store.state.groupShops.internetShops;
    },
    phoneShops() {
      return this.$store.state.groupShops.phoneShops;
    },
    formTitle() {
      return _isEmpty(this.item) ? 'New Group Shop' : 'Edit Group Shop';
    },
    secretShopper() {
      return _some(this.$auth.user().roles, { name: 'secret-shopper' });
    },
    accountManager() {
      return _some(this.$auth.user().roles, { name: 'account-manager' });
    },
    isAutomotive() {
      return this.$auth.user().dealer.is_active;
    },
    administrator() {
      return _some(this.$auth.user().roles, {
        name: 'super-administrator',
      });
    },
    filtered() {
      return this.internetShops.filter((shop) =>
        shop.title.includes(this.search.internetShops)
      );
    },
    paginated() {
      return this.filtered.slice(0, this.limit.internetShop);
    },
    hasNextPage() {
      return this.paginated.length < this.filtered.length;
    },
  },
  created() {
    this.$validator.localize('en', {
      attributes: {
        internet_shop: 'internet shop',
      },
    });

    if (this.secretShopper || this.administrator) {
      this.$store.dispatch('dealers/getDealers');
    }

    if (this.accountManager) {
      this.getSpecificDealers(this.$auth.user().dealer_id);
    }
  },
  methods: {
    selectedDealer(value) {
      this.editedItem.specific_dealer_id = '';
      if (value) {
        this.isDealerEmpty = false;
        this.getSpecificDealers(value);
        return;
      }
      return (this.isDealerEmpty = true);
    },
    getSpecificDealers(id) {
      this.loading.specificDealer = true;
      this.axios.get(`/dealer/${id}/specific-dealers`).then(({ data }) => {
        _forEach(data, (specificDealer) => {
          if (
            _findIndex(this.specificDealers, { id: specificDealer.id }) === -1
          ) {
            this.specificDealers.push(specificDealer);
          }
        });
        this.loading.specificDealer = false;
      });
    },
    save() {
      if (_isEmpty(this.item)) {
        return this.processSave();
      }
      return this.processUpdate();
    },
    extractIds(shops) {
      const id = [];
      shops.forEach((shop) => {
        return typeof shop !== 'object' ? id.push(shop) : id.push(shop.id);
      });

      return id;
    },
    processUpdate() {
      this.loading.request = true;
      let data = _cloneDeep(this.editedItem);
      data.phone_shops = this.extractIds(data.phone_shops);
      data.internet_shops = this.extractIds(data.internet_shops);
      data.dealer_id = Array.isArray(this.editedItem.dealer_id)
        ? this.editedItem.dealer_id
        : this.editedItem.dealer_id.id;
      data.specific_dealer_id = Array.isArray(
        this.editedItem.specific_dealer_id
      )
        ? this.editedItem.specific_dealer_id
        : this.editedItem.specific_dealer_id.id;
      data.id = this.item.id;
      data.hide_dealer_name = this.editedItem.hide_dealer_name;

      this.axios
        .patch(`secret-shop-management/group-shops/${this.item.id}`, data)
        .then(({ data }) => {
          this.$notify('success', 'Group shop successfully updated!');
          this.getGroupShops();
          this.close();
        })
        .catch((errors) => {
          this.interactWithErrorBag(errors, this.errors);
        })
        .finally(() => {
          this.loading.request = false;
        });
    },
    processSave() {
      this.loading.request = true;
      let data = _cloneDeep(this.editedItem);
      data.phone_shops = this.extractIds(data.phone_shops);
      data.internet_shops = this.extractIds(data.internet_shops);

      this.axios
        .post('secret-shop-management/group-shops', data)
        .then(({ data }) => {
          this.$notify('success', 'Group shop successfully created!');
          this.getGroupShops();
          this.close();
        })
        .catch((errors) => {
          this.interactWithErrorBag(errors, this.errors);
        })
        .finally(() => {
          this.loading.request = false;
        });
    },
    close() {
      this.dialog = false;
      this.editedItem.name = '';
      this.editedItem.dealer_id = '';
      this.editedItem.specific_dealer_id = '';
      this.editedItem.internet_shops = [];
      this.editedItem.phone_shops = [];
      this.editedItem.hide_dealer_name = false;
    },
    setEditForm() {
      this.editedItem.name = this.item.name;
      this.editedItem.dealer_id = this.item.dealer;
      this.editedItem.specific_dealer_id = this.item.specific_dealer;
      this.editedItem.internet_shops = this.item.internet_shops;
      this.editedItem.phone_shops = this.item.phone_shops;
      this.editedItem.hide_dealer_name = this.item.hide_dealer_name;
    },
    setShopDropdownValue(type, value) {
      this.editedItem[type] = value;
    },
  },
};
</script>

<style scoped></style>
