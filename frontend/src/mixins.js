import { each as _each, forEach as _forEach, head as _head } from 'lodash-es';

export default {
  methods: {
    interactWithErrorBag(errors, bag) {
      const messages = errors.response.data.errors;
      _forEach(messages, (value, key) => {
        bag.add({
          field: key,
          msg: _head(value),
        });
      });
    },
    getDealers() {
      const dealersList = [];
      this.$http.get('/dealers').then((response) => {
        _each(response.data, (dealer) => {
          dealersList.push({
            name: dealer.name,
            id: dealer.id,
          });
        });
      });

      return dealersList;
    },
    getSpecificDealers() {
      const specificDealersList = [];
      this.$http.get('/dealers/specific').then((response) => {
        _each(response.data, (dealer) => {
          specificDealersList.push({
            name: dealer.name,
            id: dealer.id,
            dealer_id: dealer.dealer_id,
            managers: dealer.managers,
          });
        });
      });

      return specificDealersList;
    },
    formatDateRangeForTextField(date) {
      if (date) {
        date.filter(function (str) {
          return /\S/.test(str);
        });
        date = date.map(function (date) {
          if (date) {
            return new Date(date).toLocaleDateString('en-US');
          }
        });
      }
      return date.join('~');
    },
  },
};
