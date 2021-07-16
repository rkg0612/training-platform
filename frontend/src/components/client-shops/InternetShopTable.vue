<template>
  <div class="kt-portlet kt-portlet--height-fluid pt-2">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--breaker-sm flex-wrap"
    >
      <div class="kt-portlet__head-label mb-2">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-globe" />
        </span>
        <h3 class="kt-portlet__head-title">Internet Shops</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="shops"
        :server-items-length="pagination.total"
        :items-per-page="options.itemsPerPage"
        :loading="isFetchingData"
        sort-by="dealers"
        class="elevation-1"
        :options.sync="options"
        @update:page="updatePage"
        @update:sort-by="updateSorting"
        @update:sort-desc="updateSorting"
      >
        <template v-slot:item.dateShopped="{ item }">
          <span>{{ item.dateShopped | displayDateTime }}</span>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn text small color="primary" @click="viewData(item)">
            <v-icon class="mr-2"> fal fa-eye </v-icon>
            View Data
          </v-btn>
        </template>
        <template v-slot:top>
          <div class="px-3">
            <div class="row no-gutters pt-3">
              <div class="col-xl-4">
                <v-text-field
                  class="mt-0 pt-0"
                  v-model.trim="search"
                  @change="searchInternetShop"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
              </div>
            </div>
          </div>
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
  head as _head,
  debounce as _debounce,
  some as _some,
  isEmpty as _isEmpty,
} from 'lodash-es';

export default {
  name: 'InternetShopTable',
  data() {
    return {
      search: '',
      pagination: {
        currentPage: 1,
        total: 1,
      },
      options: {
        groupBy: [],
        groupDesc: [],
        itemsPerPage: 10,
        multiSort: false,
        mustSort: false,
        page: 1,
        sortBy: ['dateShopped'],
        sortDesc: [],
      },
      isFetchingData: false,
      headers: [
        {
          text: 'Competitor Dealer',
          sortable: true,
          value: 'competitor',
        },
        {
          text: 'Make',
          value: 'make',
          sortable: true,
        },
        {
          text: 'Model',
          value: 'model',
          sortable: true,
        },
        {
          text: 'Shop Type',
          value: 'shop_type',
          sortable: true,
        },
        {
          text: 'Type of Dealer',
          value: 'type_of_dealer',
          sortable: true,
        },
        {
          text: 'Date Shopped',
          value: 'dateShopped',
          sortable: true,
        },
        {
          text: 'Actions',
          value: 'actions',
          sortable: false,
        },
      ],
      shops: [],
    };
  },
  created() {
    this.fetchData();
    this.setHeaders();
  },
  methods: {
    viewData({ id }) {
      return this.$router.push({
        name: 'InternetShop',
        params: {
          id,
        },
      });
    },
    setHeaders() {
      const roles = this.$auth.user().roles;
      const isAdmin = _some(roles, { name: 'super-administrator' });
      const isAccountManager = _some(roles, { name: 'account-manager' });
      const isSecretShopper = _some(roles, { name: 'secret-shopper' });
      const isSalesperson = _some(roles, { name: 'salesperson' });
      if (
        isAdmin ||
        isSecretShopper ||
        (isSalesperson && _isEmpty(this.$auth.user().specific_dealer_id))
      ) {
        this.headers.splice(0, 0, {
          text: 'Company',
          align: 'left',
          sortable: true,
          value: 'dealer',
        });
      }
      if (
        isAccountManager ||
        isAdmin ||
        isSecretShopper ||
        (isSalesperson && !_isEmpty(this.$auth.user().specific_dealer_id))
      ) {
        this.headers.splice(1, 0, {
          text: 'Specific Dealer',
          sortable: true,
          value: 'specific_dealer',
        });
      }
      // if (this.$auth.user().dealer.name === 'TrueCar') {
      //   this.headers.splice(4, 0, {
      //     text: 'RN',
      //     sortable: true,
      //     value: 'csm_name',
      //   });
      // }
    },
    searchInternetShop: _debounce(function () {
      this.options.page = 1;
      this.fetchData();
    }, 500),
    updatePage(page) {
      this.options.page = page;
      this.fetchData();
    },
    fetchData() {
      this.isFetchingData = true;
      const sortBy = _head(this.options.sortBy);
      const sortDesc = _head(this.options.sortDesc);
      this.$http
        .get('/clients/secret-shops/internet-shops', {
          params: {
            page: this.options.page,
            perPage: this.options.itemsPerPage,
            sortBy,
            sortDesc,
            search: this.search,
          },
        })
        .then(({ data }) => {
          this.isFetchingData = false;
          this.setPaginationData(data.meta);
          this.shops = data.data;
        })
        .catch((error) => {
          this.isFetchingData = false;
        });
    },
    setPaginationData(meta) {
      this.pagination.total = meta.total;
    },
    updateSorting() {
      this.options.page = 1;
      this.fetchData();
    },
  },
};
</script>

<style scoped></style>
