<template>
  <v-card>
    <v-card-title>
      <h3 class="headline">
        <v-icon class="m-2">fal fa-shopping-cart</v-icon>
        Secret Shops
      </h3>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="fal fa-search"
        label="Search"
        messages="You can search by Dealer's name..."
        single-line
        @input="filterSecretShop"
        :disabled="loading.fetchSecretShops"
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="secretShops"
      :items-per-page="paginate.itemsPerPage"
      :loading="loading.fetchSecretShops"
      :disabled="loading.fetchSecretShops"
      :options="paginate"
      :server-items-length="totalItemsLength"
      @update:page="pageChanged"
      @update:sort-by="sortBy"
      @update:sort-desc="sortByDesc"
    >
      <template v-slot:item.actions="{ item }">
        <v-btn text small color="primary" @click="viewData(item)">
          <v-icon class="mr-2"> fal fa-eye </v-icon>
          View Data
        </v-btn>
      </template>
    </v-data-table>
  </v-card>
</template>
<script>
import { debounce as _debounce, head as _head } from 'lodash-es';

export default {
  name: 'ShopsTable',
  data() {
    return {
      search: '',
      loading: {
        fetchSecretShops: false,
      },
      scrollSync: {
        top: 0,
        left: 0,
      },
      headers: [
        {
          text: 'Action',
          value: 'actions',
          align: 'center',
        },
        {
          text: 'Name of Shopped Dealer',
          value: 'specificDealerName',
          align: 'center',
          sortable: true,
          filterable: true,
          width: 250,
        },
        {
          text: 'Date Shopped',
          value: 'dateShopped',
          align: 'center',
          sortable: true,
          filterable: true,
          width: 200,
        },
        {
          text: 'Type of Dealer',
          value: 'dealerType',
          align: 'center',
          sortable: true,
          filterable: true,
          width: 150,
        },
        {
          text: 'Type of Shop',
          value: 'shopType',
          align: 'center',
          sortable: true,
          filterable: true,
          width: 150,
        },
        {
          text: 'Make',
          value: 'make',
          align: 'center',
          sortable: true,
          filterable: true,
          width: 150,
        },
        {
          text: 'Model',
          value: 'model',
          align: 'center',
          sortable: true,
          filterable: true,
          width: 150,
        },
        {
          text: 'Name of Salesperson',
          value: 'salespersonsName',
          align: 'center',
          sortable: true,
          filterable: true,
          width: 200,
        },
      ],
      paginate: {
        sortBy: ['dateShopped'],
        sortDesc: [true],
        page: 1,
        itemsPerPage: 10,
      },
      totalItemsLength: 1,
    };
  },
  computed: {
    secretShops() {
      return this.$store.state.secretShopsTable.list;
    },
  },
  created() {
    this.fetchSecretShops();
    this.addColumnToHeader();
  },
  methods: {
    addColumnToHeader() {
      const dealerColumn = {
        text: 'Dealer / Automotive Group',
        value: 'dealerName',
        align: 'center',
        sortable: true,
        filterable: true,
        width: 200,
      };
      const rnColumn = {
        text: 'RN',
        value: 'csmName',
        align: 'center',
        sortable: true,
        filterable: true,
        width: 150,
      };
      const role = _head(this.$auth.user().roles);

      if (role.name === 'super-administrator') {
        this.headers.splice(1, 0, dealerColumn);
      }

      if (
        this.$auth.user().dealer.name === 'TrueCar' ||
        role.name === 'super-administrator'
      ) {
        this.headers.splice(6, 0, rnColumn);
      }
    },
    fetchSecretShops() {
      this.loading.fetchSecretShops = true;
      this.$http
        .get(`/secret-shops-table?`, {
          params: {
            page: this.paginate.page,
            search: this.search,
            sortBy: _head(this.paginate.sortBy),
            sortDesc: _head(this.paginate.sortDesc),
          },
        })
        .then(({ data }) => {
          this.$store.commit('secretShopsTable/setSecretShops', data.data);
          this.paginate.itemsPerPage = data.per_page;
          this.totalItemsLength = data.total;
          this.loading.fetchSecretShops = false;
        })
        .catch((error) => {
          this.loading.fetchSecretShops = false;
        });
    },
    viewData(item) {
      const { id } = item;

      if (item.shopType === 'Phone Shop') {
        return this.$router.push({
          name: 'PhoneShop',
          params: {
            id,
          },
        });
      }

      return this.$router.push({
        name: 'InternetShop',
        params: {
          id,
        },
      });
    },
    pageChanged(page) {
      this.paginate.page = page;
      this.fetchSecretShops();
    },
    filterSecretShop: _debounce(function () {
      this.paginate.page = 1;
      this.fetchSecretShops();
    }, 700),
    sortByDesc(desc) {
      this.paginate.page = 1;
      this.paginate.sortDesc = desc;
      this.fetchSecretShops();
    },
    sortBy(sortBy) {
      this.paginate.page = 1;
      this.paginate.sortBy = sortBy;
      this.fetchSecretShops();
    },
  },
};
</script>
