<template>
  <vue-select
    @search="(query) => (search = query)"
    :filterable="false"
    v-model="model"
    :options="paginated"
    name="internet_shop"
    v-validate="'required'"
    multiple
    label="title"
    @open="onOpen"
    @close="onClose"
  >
    <template #list-footer>
      <li ref="load" class="loader" v-show="hasNextPage">
        Loading more options...
      </li>
    </template>
  </vue-select>
</template>

<script>
export default {
  name: 'ShopSelectDropdown',
  props: ['shopType', 'value', 'setShopDropdownValue'],
  data() {
    return {
      observer: null,
      limit: 10,
      search: '',
      page: 1,
    };
  },
  mounted() {
    /**
     * You could do this directly in data(), but since these docs
     * are server side rendered, IntersectionObserver doesn't exist
     * in that environment, so we need to do it in mounted() instead.
     */
    this.observer = new IntersectionObserver(this.infiniteScroll);
  },
  computed: {
    internetShopType() {
      return this.shopType === 'internetShop';
    },
    filtered() {
      const type = this.internetShopType ? 'internetShops' : 'phoneShops';
      const search = this.search.toLowerCase();

      return this[type].filter((item) =>
        item.title.toLowerCase().includes(search)
      );
    },
    paginated() {
      return this.filtered.slice(0, this.limit);
    },
    hasNextPage() {
      return this.paginated.length < this.filtered.length;
    },
    internetShops() {
      return this.$store.state.groupShops.internetShops;
    },
    phoneShops() {
      return this.$store.state.groupShops.phoneShops;
    },
    model: {
      get() {
        return this.value;
      },
      set(value) {
        const type = this.internetShopType ? 'internet_shops' : 'phone_shops';
        this.setShopDropdownValue(type, value);
      },
    },
  },
  methods: {
    async onOpen() {
      if (this.hasNextPage) {
        await this.$nextTick();
        this.observer.observe(this.$refs.load);
      }
    },
    onClose() {
      this.observer.disconnect();
    },
    async infiniteScroll([{ isIntersecting, target }]) {
      if (isIntersecting) {
        const ul = target.offsetParent;
        const { scrollTop } = target.offsetParent;
        this.limit += 10;
        this.page += 1;
        await this.getData();

        await this.$nextTick();
        ul.scrollTop = scrollTop;
      }
    },
    async getData() {
      const urlType = this.internetShopType ? 'internet-shops' : 'phone-shops';
      const dispatchType = this.internetShopType
        ? 'setInternetShops'
        : 'setPhoneShops';

      await this.$http
        .get(`/secret-shop-management/${urlType}?page=${this.page}`, {
          params: {
            search: this.search,
            type: ['Active'],
            perPage: 20,
            isGroupShop: true,
          },
        })
        .then(({ data }) => {
          this.$store.dispatch(`groupShops/${dispatchType}`, data.data);
        })
        .catch(() => {
          this.$notify(
            'error',
            'Error when loading the data. Please refresh the page and try again.'
          );
        });
    },
  },
  created() {
    this.getData();
  },
};
</script>

<style scoped></style>
