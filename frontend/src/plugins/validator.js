import { Validator } from 'vee-validate';

const extendOptions = { hasTarget: true };

const validator = {
  getMessage(field, [target]) {
    return `The field ${target} must have values.`;
  },
  validate(value, [args]) {
    return !!args;
  },
};

Validator.extend('truthy', validator, extendOptions);
