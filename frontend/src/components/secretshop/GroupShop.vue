<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-layer-group"></i>
        </span>
        <h3 class="kt-portlet__head-title">Group Shop Management</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
          <group-shop-form
            v-model="showDialog"
            :item="selectedItem"
            :get-group-shops="initialize"
          />
          <v-btn
            color="primary"
            dark
            class="mb-2 mt-2 mt-sm-0 mx-auto"
            @click="showDialog = true"
            text
          >
            <i class="fal fa-plus mr-1"></i>
            New Group Shop
          </v-btn>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :loading="loading"
        :headers="headers"
        :items="groupShops"
        :search="groupShopSearch"
        @update:page="changePage"
        @update:sort-by="updateSortBy"
        @update:sort-desc="updateSortOrder"
        :server-items-length="pagination.total"
        sort-by="name"
        class="elevation-1"
      >
        <template v-slot:top>
          <div class="px-3">
            <div class="row no-gutters pt-3">
              <div class="col-xl-4">
                <v-select
                  class="mt-0 pt-0"
                  :loading="loading"
                  :disabled="loading"
                  @change="initialize"
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
                  v-model="groupShopSearch"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                  @keydown.enter="searchGroupShops"
                />
              </div>
            </div>
          </div>
        </template>
        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | displayDateTime }}
        </template>
        <template v-slot:item.action="{ item }">
          <template v-if="item.deleted_at === null">
            <v-btn text @click="viewItem(item)" color="blue darken-3" small>
              <v-icon small>fal fa-eye</v-icon>
            </v-btn>
            <v-btn text @click="editItem(item)" color="grey darken-3" small>
              <v-icon small>fal fa-edit</v-icon>
            </v-btn>
            <v-btn text @click="deleteItem(item)" color="red darken-3" small>
              <v-icon small>fal fa-trash</v-icon>
            </v-btn>
          </template>
          <template v-else>
            <v-btn color="primary" @click="restoreItem(item)" text small>
              Restore
            </v-btn>
          </template>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>

<script>
import {
  isEmpty as _isEmpty,
  debounce as _debounce,
  some as _some,
} from 'lodash-es';
import GroupShopForm from './GroupShop/GroupShopForm';

export default {
  name: 'GroupShop',
  components: {
    GroupShopForm,
  },
  data() {
    return {
      showDialog: false,
      groupShopSearch: '',
      headers: [
        {
          text: 'Name',
          align: 'left',
          sortable: false,
          value: 'name',
        },
        {
          text: 'Specific Dealer',
          align: 'center',
          sortable: true,
          value: 'specific_dealer.name',
        },
        {
          text: 'Date Created',
          align: 'center',
          sortable: true,
          value: 'created_at',
        },
      ],
      selectedItem: {},
      pagination: {
        total: 1,
        current_page: 1,
        per_page: 5,
        sortBy: 'created_at',
        sortDesc: true,
      },
      loading: false,
      filters: [],
    };
  },
  computed: {
    groupShops() {
      return this.$store.state.groupShops.list;
    },
    isAdminOrShopper() {
      return (
        _some(this.$auth.user().roles, {
          friendly_name: 'Super Administrator',
        }) ||
        _some(this.$auth.user().roles, {
          friendly_name: 'Secret Shopper',
        })
      );
    },
  },
  created() {
    this.filters = ['Active', 'Inactive'];
    this.initialize();
    if (this.isAdminOrShopper) {
      const shopperHeader = {
        text: 'Secret Shopper',
        align: 'center',
        sortable: true,
        value: 'secret_shopper.name',
      };
      this.headers.splice(3, 0, shopperHeader);
      const actionsHeader = {
        text: 'Action',
        align: 'center',
        value: 'action',
        width: 200,
        sortable: false,
      };
      this.headers.splice(4, 0, actionsHeader);
    }
  },
  watch: {
    showDialog() {
      if (!_isEmpty(this.selectedItem) && !this.showDialog) {
        this.selectedItem = {};
      }
    },
  },
  methods: {
    searchGroupShops() {
      this.searchShops();
    },
    searchShops: _debounce(function () {
      this.initialize();
    }, 500),
    toggleAllFilter() {
      this.filters = ['Active', 'Inactive'];
      this.initialize();
    },
    initialize() {
      this.loading = true;
      this.axios
        .get('secret-shop-management/group-shops', {
          params: {
            per_page: this.pagination.per_page,
            page: this.pagination.current_page,
            sortBy: this.pagination.sortBy,
            sortDesc: this.pagination.sortDesc,
            types: this.filters,
            search: this.groupShopSearch,
          },
        })
        .then(({ data }) => {
          this.$store.dispatch('groupShops/setList', data.data);
          this.pagination.current_page = data.meta.current_page;
          this.pagination.per_page = data.meta.per_page;
          this.pagination.total = data.meta.total;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    editItem(item) {
      this.selectedItem = item;
      this.showDialog = true;
    },
    changePage(page) {
      this.pagination.current_page = page;
      this.initialize();
    },
    updateSortBy(sortBy) {
      this.pagination.sortBy = sortBy;
      this.initialize();
    },
    updateSortOrder(sortOrder) {
      this.pagination.sortDesc = sortOrder;
      this.initialize();
    },
    deleteItem(item) {
      this.loading = true;
      this.axios
        .delete(`/secret-shop-management/group-shops/${item.id}`)
        .then(({ data }) => {
          this.$notify('success', 'Group shop archived!');
          this.initialize();
        })
        .finally(() => {
          this.loading = false;
        });
    },
    restoreItem(item) {
      this.axios
        .patch(`/secret-shop-management/group-shops/restore/${item.id}`)
        .then(({ data }) => {
          this.$notify('success', 'Group shop restored!');
          this.initialize();
        })
        .finally(() => {
          this.loading = false;
        });
    },
    viewItem(item) {
      this.$router.push({
        name: 'GroupShop',
        params: {
          id: item.id,
        },
      });
    },
  },
};
</script>

<style lang="stylus">
.group-shop-btn
  margin 0px 4px
@media screen and (max-width: 856px)
  .group-shop-btn
    display block!important
    margin 4px auto
@media screen and (max-width: 599px)
  .group-shop-btn
    display inline-block!important
    margin 0px 4px
</style>
