<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--breaker-sm flex-wrap"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fas fa-phone"></i>
        </span>
        <h3 class="kt-portlet__head-title">Manage Phone Shops</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
          <phone-shop-form
            v-model="showPhoneShopForm"
            :phone-shop="phoneShop"
            :fetch-phone-shop-records="fetchPhoneShops"
          />
          <v-btn
            color="blue darken-3"
            dark
            class="mb-2 px-3"
            @click.stop="openForm"
            text
          >
            <i class="fal fa-plus mr-1"></i>
            New Phone Shop
          </v-btn>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :options.sync="options"
        :server-items-length="pagination.total"
        :loading="loading.fetching"
        :disabled="loading.fetching"
        :headers="headers"
        :items="phoneShopList"
        :search="phoneShopSearch"
        @update:page="changePage"
        @update:sort-by="fetchPhoneShops"
        @update:sort-desc="fetchPhoneShops"
        :items-per-page="options.per_page"
        :options="options"
        class="elevation-1"
      >
        <template v-slot:top>
          <div class="px-3">
            <div class="row no-gutters pt-3">
              <div class="col-xl-4">
                <v-select
                  class="mt-0 pt-0"
                  :loading="loading.fetching"
                  :disabled="loading.fetching"
                  @change="fetchPhoneShops"
                  v-model="types"
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
                  v-model="phoneSearch"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                  @keydown.enter="searchPhoneShops"
                />
              </div>
            </div>
          </div>
        </template>
        <template v-slot:item.dealer="{ item }">
          {{ item.dealer.name }}
        </template>
        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | displayDateTime('EST') }}
        </template>
        <template v-slot:item.shop_type="{ item }">
          {{ item.shop_type | shopType }}
        </template>
        <template v-slot:item.action="{ item }">
          <div v-if="item.deleted_at === null">
            <v-btn text @click="viewItem(item)" color="blue darken-3" small>
              <v-icon small>fal fa-eye</v-icon>
            </v-btn>
            <v-btn text @click="editItem(item)" color="grey darken-3" small>
              <v-icon small>fal fa-edit</v-icon>
            </v-btn>
            <v-btn text @click="deleteItem(item)" color="red darken-3" small>
              <v-icon small>fal fa-trash</v-icon>
            </v-btn>
          </div>
          <div v-else>
            <button
              v-if="item.deleted_at !== null"
              type="button"
              class="btn btn-label-dark btn-sm btn-icon"
              @click="restoreItem(item)"
            >
              <i class="far fa-trash-restore" />
            </button>
          </div>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="fetchPhoneShops"> Reset </v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import { findIndex as _findIndex, head as _head } from 'lodash-es';
import PhoneShopForm from './PhoneShopForm';
import PhoneShop from '../../views/PhoneShop';

export default {
  name: 'SecretshopPost',
  components: {
    PhoneShop,
    PhoneShopForm,
  },
  data: () => ({
    showPhoneShopForm: false,
    phoneShopSearch: '',
    editorData: '',
    loading: {
      fetching: false,
    },
    phoneShop: {},
    types: '',
    phoneSearch: '',
    pagination: {
      current_page: 1,
      per_page: 5,
      total: 1,
    },
    options: {
      groupBy: [],
      groupDesc: [],
      itemsPerPage: 5,
      multiSort: false,
      mustSort: false,
      page: 1,
      sortBy: ['created_at'],
      sortDesc: [],
    },
    headers: [
      {
        text: 'Company',
        align: 'left',
        sortable: true,
        value: 'dealer',
      },
      {
        text: 'Specific Dealer',
        sortable: true,
        value: 'specific_dealer.name',
      },
      {
        text: 'Secret Shopper',
        value: 'secret_shopper.name',
        sortable: true,
      },
      {
        text: 'Date Created',
        value: 'created_at',
        sortable: true,
      },
      {
        text: 'Shop Type',
        value: 'shop_type',
        sortable: true,
      },
      {
        text: 'Actions',
        value: 'action',
        align: 'center',
        width: 200,
        sortable: false,
      },
    ],
    shops: [],
  }),

  computed: {
    phoneShopList() {
      return this.$store.state.phoneShops.phoneShopList;
    },
  },

  watch: {
    showPhoneShopForm() {
      if (!this.showPhoneShopForm) {
        this.phoneShop = {};
      }
    },
  },

  created() {
    this.toggleAllFilter();
    this.fetchPhoneShops();
    this.$store
      .dispatch('specificDealers/getSpecificDealers')
      .then(({ data }) => {
        this.$store.commit('specificDealers/setSpecificDealers', data);
      });
  },

  filters: {
    shopType(shop_type) {
      return shop_type ? 'New' : 'Used';
    },
  },

  methods: {
    searchPhoneShops(query) {
      if (this.source) {
        this.source.cancel();
      }
      this.source = this.axios.CancelToken.source();
      try {
        this.axios
          .get('/secret-shop-management/phone-shops', {
            params: {
              search: query.target.value,
            },
            cancelToken: this.source.token,
          })
          .then(({ data }) => {
            this.pagination.current_page = data.meta.current_page;
            this.pagination.total = data.meta.total;
            this.options.itemsPerPage = Number(data.meta.per_page);
            this.$store.commit('phoneShops/setPhoneShops', data.data);
            this.options.page = data.meta.current_page;
            this.loading.fetching = false;
          });
      } catch (error) {
        this.loading.fetching = false;
        if (this.axios.isCancel(error)) {
          // cancelled by the use
        }
      }
    },

    changePage(page) {
      this.options.page = page;
      this.fetchPhoneShops();
    },

    toggleAllFilter() {
      this.types = ['Active', 'Inactive'];
      this.fetchPhoneShops();
    },

    fetchPhoneShops() {
      this.phoneShopSearch = '';
      if (this.source) {
        this.source.cancel();
      }
      this.source = this.axios.CancelToken.source();
      const sortBy = _head(this.options.sortBy);
      const sortDesc = _head(this.options.sortDesc);
      this.loading.fetching = true;
      this.$http
        .get('/secret-shop-management/phone-shops', {
          params: {
            page: this.options.page,
            perPage: this.options.itemsPerPage,
            type: this.types,
            sortBy,
            sortDesc,
          },
          cancelToken: this.source.token,
        })
        .then(({ data }) => {
          this.pagination.current_page = data.meta.current_page;
          this.pagination.total = data.meta.total;
          this.options.itemsPerPage = Number(data.meta.per_page);
          this.$store.commit('phoneShops/setPhoneShops', data.data);
          this.options.page = data.meta.current_page;
          this.loading.fetching = false;
        })
        .catch(() => {
          if (!this.types.length) {
            this.$notify('warning', 'Please select a filter/types...');
          }
        });
    },

    restoreItem(item) {
      const { id } = item;
      this.$store
        .dispatch('phoneShops/restorePhoneShop', id)
        .then(({ data }) => {
          this.$notify(
            'success',
            'Phone Shop Deleted item successfully restored!'
          );
          this.fetchPhoneShops();
        });
    },

    editItem(item) {
      this.phoneShop = item;
      this.showPhoneShopForm = true;
    },

    deleteItem(item) {
      this.editedIndex = _findIndex(this.phoneShopList, { id: item.id });
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
          this.$store
            .dispatch('phoneShops/deletePhoneShop', item.id)
            .then(({ data }) => {
              if (data.success) {
                this.fetchPhoneShops();
                this.$notify(
                  'success',
                  'Phone shop set status to invalid sucessfully!'
                );
              }
            });
        }
      });
    },

    viewItem(item) {
      const { id } = item;
      this.$router.push({
        name: 'PhoneShop',
        params: {
          id,
        },
      });
    },

    openForm() {
      this.showPhoneShopForm = true;
      this.$store.commit('phoneShops/setSelectedItem', {});
    },
  },
};
</script>
