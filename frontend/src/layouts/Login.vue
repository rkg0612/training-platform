<template>
  <v-app>
    <div class="kt-grid kt-grid--ver kt-grid--root">
      <div
        class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin"
        id="kt_login"
      >
        <div
          class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
          id="login-bg"
        >
          <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
            <div class="kt-login__container">
              <div class="kt-login__logo">
                <a href="#">
                  <img
                    v-if="isHudsonAcademy"
                    src="https://webinarinc-app.s3-us-west-1.amazonaws.com/images/image-0b03d740-deb3-11e8-91fb-93df1affeb65.png"
                    width="350"
                  />
                  <img
                    v-else
                    src="../assets/images/main-logo.png"
                    width="350"
                  />
                </a>
                <h3 v-if="!isHudsonAcademy" class="kt-login__title">
                  For Car People, By Car People
                </h3>
                <p
                  class="kt-font-danger text-center"
                  :class="signInError ? '' : 'd-none'"
                >
                  {{ signInErrorMessage }}
                </p>
              </div>
              <div class="kt-login__signin" :class="signInTab ? '' : 'd-none'">
                <form class="kt-form" v-on:submit.prevent>
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="text"
                      placeholder="Email"
                      name="email"
                      autocomplete="off"
                      v-model="email"
                    />
                  </div>
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="password"
                      placeholder="Password"
                      name="password"
                      v-model="password"
                    />
                  </div>
                  <div class="row kt-login__extra">
                    <div class="col">
                      <label class="kt-checkbox">
                        <input type="checkbox" name="remember" />
                        Remember me
                        <span></span>
                      </label>
                    </div>
                    <div class="col kt-align-right">
                      <a
                        href="javascript:;"
                        id="kt_login_forgot"
                        class="kt-link kt-login__link"
                        @click="showTab('forgetPasswordTab')"
                      >
                        Forgot Password?
                      </a>
                    </div>
                  </div>
                  <div class="kt-login__actions">
                    <button
                      id="kt_login_signin_submit"
                      class="btn btn-pill kt-login__btn-primary mr-2"
                      @click="signIn"
                      :class="
                        loader /* eslint-disable */
                          ? 'kt-spinner kt-spinner--v2 kt-spinner--right kt-spinner--sm kt-spinner--warning'
                          : ''
                      "
                    >
                      Sign In
                    </button>
                    <!-- <div class="kt-login__divider mt-3">
                      <div class="kt-divider">
                        <span></span>
                        <span id="divider">OR</span>
                        <span></span>
                      </div>
                    </div>
                    <div class="kt-login__options mt-3">
                      <button class="btn btn-pill btn-outlined btn-info kt-btn">
                        <i class="fab fa-salesforce"></i>
                        Sign in with Salesforce
                      </button>
                    </div> -->
                    <div class="kt-login__account">
                      <span class="kt-login__account-msg">
                        Don't have an account yet ?
                      </span>
                      <v-spacer />
                      <v-spacer />
                      <a
                        href="javascript:;"
                        @click="showTab('signUpTab')"
                        id="kt_login_signup"
                        class="kt-login__account-link"
                        >Sign Up!</a
                      >
                    </div>
                  </div>
                </form>
              </div>
              <div
                class="kt-login__signup displayBlock"
                :class="signUpTab ? '' : 'd-none'"
              >
                <div class="kt-form">
                  <div class="kt-login__head">
                    <h3 class="kt-login__title">Sign Up</h3>
                    <div class="kt-login__desc">Enter your details below:</div>
                  </div>
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="text"
                      placeholder="Full Name"
                      name="fullname"
                      v-validate="'required'"
                      v-model="signUpForm.name"
                    />
                  </div>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('name')"
                  >
                    {{ errors.first('name') }}
                  </span>
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="text"
                      placeholder="Email"
                      v-model="signUpForm.email"
                      autocomplete="off"
                      v-validate="'required|email'"
                      name="email"
                    />
                  </div>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('email')"
                  >
                    {{ errors.first('email') }}
                  </span>
                  <div class="registration-select">
                    <multiselect
                      v-model="signUpForm.dealer"
                      class="mt-5"
                      tag-placeholder="Add this as the dealer you are part of."
                      placeholder="Name of Your Company"
                      label="name"
                      :options="dealersList"
                      :multiple="false"
                      :taggable="true"
                      :limit="3"
                      @search-change="searchDealers"
                      @tag="addNewDealer"
                      track-by="id"
                    >
                      <template slot="noOptions" slot-scope="props">
                        Enter Company Name
                      </template>
                    </multiselect>
                  </div>
                  <div class="registration-select">
                    <v-select
                      class="mt-5"
                      :items="managersList"
                      item-text="name"
                      item-value="id"
                      label="Managers"
                      dark
                      outlined
                      v-if="managersList != null"
                      v-model="signUpForm.manager"
                    >
                    </v-select>
                  </div>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('selectedDealerAtSignup')"
                  >
                    {{ errors.first('selectedDealerAtSignup') }}
                  </span>
                  <div class="row kt-login__extra">
                    <div class="col kt-align-left">
                      <label class="kt-checkbox">
                        <input
                          type="checkbox"
                          v-model="signUpForm.terms_and_conditions"
                          value="1"
                          v-validate="'required'"
                          name="terms_and_conditions"
                        />I Agree to the
                        <a href="#" class="kt-link kt-login__link kt-font-bold"
                          >terms and conditions</a
                        >.
                        <span></span>
                      </label>
                    </div>
                  </div>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('terms_and_conditions')"
                  >
                    {{ errors.first('terms_and_conditions') }}
                  </span>
                  <div class="kt-login__actions">
                    <button
                      @click="submitSignUp"
                      id="kt_login_signup_submit"
                      class="btn btn-brand btn-pill kt-login__btn-primary"
                      :class="
                        loader /* eslint-disable */
                          ? 'kt-spinner kt-spinner--v2 kt-spinner--right kt-spinner--sm kt-spinner--warning'
                          : ''
                      "
                    >
                      Sign Up
                    </button>
                    <v-spacer />
                    <v-spacer />
                    <button
                      @click="closeSignUp"
                      id="kt_login_signup_cancel"
                      class="btn btn-secondary btn-pill kt-login__btn-secondary"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
              <div
                class="kt-login__signup displayBlock"
                :class="resetPasswordTab ? '' : 'd-none'"
              >
                <div class="kt-form">
                  <div class="kt-login__head">
                    <h3 class="kt-login__title">Reset Password Form</h3>
                    <div class="kt-login__desc">Reset Your Password Below:</div>
                  </div>
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="text"
                      placeholder="Email"
                      name="email"
                      v-validate="'required|email'"
                      v-model="resetPasswordForm.email"
                    />
                  </div>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('email')"
                  >
                    {{ errors.first('email') }}
                  </span>
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="password"
                      placeholder="Password"
                      name="password"
                      v-validate="'required|confirmed:password_confirmation'"
                      v-model="resetPasswordForm.password"
                    />
                  </div>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('password')"
                  >
                    {{ errors.first('password') }}
                  </span>

                  <div class="input-group">
                    <input
                      class="form-control"
                      type="password"
                      placeholder="Password Confirmation"
                      name="password_confirmation"
                      v-validate="'required'"
                      v-model="resetPasswordForm.password_confirmation"
                      ref="password_confirmation"
                    />
                  </div>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('password_confirmation')"
                  >
                    {{ errors.first('password_confirmation') }}
                  </span>
                  <div class="kt-login__actions">
                    <button
                      @click="resetPassword"
                      class="btn btn-brand btn-pill kt-login__btn-primary"
                      :class="
                        loader /* eslint-disable */
                          ? 'kt-spinner kt-spinner--v2 kt-spinner--right kt-spinner--sm kt-spinner--warning'
                          : ''
                      "
                    >
                      Submit
                    </button>
                    <v-spacer />
                    <v-spacer />
                    <button
                      @click="closeSignUp"
                      id="kt_login_signup_cancel"
                      class="btn btn-secondary btn-pill kt-login__btn-secondary"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
              <div
                class="kt-login__forgot displayBlock"
                :class="forgetPasswordTab ? '' : 'd-none'"
              >
                <div class="kt-login__head">
                  <h3 class="kt-login__title">Forgotten Password?</h3>
                  <div class="kt-login__desc">
                    Enter your email to reset your password:
                  </div>
                </div>
                <form class="kt-form" action="">
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="text"
                      placeholder="Email"
                      name="email"
                      id="kt_email"
                      autocomplete="off"
                      v-model="emailOfForgottenAccountPassword"
                      v-validate="'required|email'"
                    />
                  </div>
                  <div class="kt-login__actions">
                    <button
                      id="kt_login_forgot_submit"
                      class="btn btn-pill kt-login__btn-primary"
                      @click="submitForgotPasswordRequest"
                      type="button"
                    >
                      Request
                    </button>
                    <v-spacer /><v-spacer />
                    <button
                      id="kt_login_forgot_cancel"
                      class="btn btn-pill kt-login__btn-secondary"
                    >
                      Cancel
                    </button>
                    <div class="kt-login__account">
                      <span class="kt-login__account-msg">
                        Don't have an account yet ?
                      </span>
                      <v-spacer />
                      <v-spacer />
                      <a
                        href="javascript:;"
                        @click="showTab('signUpTab')"
                        id="kt_login_signup"
                        class="kt-login__account-link"
                        >Sign Up!</a
                      >
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import Password from 'vue-password-strength-meter';
import '../mixins';
import {
  forEach as _forEach,
  head as _head,
  isString as _isString,
  each as _each,
} from 'lodash-es';

export default {
  name: 'Login',
  components: {
    Password,
  },
  data() {
    return {
      dealersList: [],
      placeHolderForDealersList: [],
      isLoading: false,
      search: null,
      signInTab: true,
      signUpTab: false,
      forgetPasswordTab: false,
      resetPasswordTab: false,
      email: '',
      emailOfForgottenAccountPassword: '',
      password: '',
      loader: false,
      signInError: false,
      signInErrorKey: '',
      signUpError: false,
      signUpForm: {
        full_name: '',
        email: '',
        dealer: null,
        terms_and_conditions: false,
      },
      resetPasswordForm: {
        email: '',
        password: '',
        password_confirmation: '',
      },
      success: false,
    };
  },
  created() {
    if (this.$route.params.token !== undefined) {
      this.showTab('resetPasswordTab');
    }
    this.placeHolderForDealersList = this.getSpecificDealers();
    this.$validator.localize('en', {
      attributes: {
        name: 'Full name',
        email: 'Email',
      },
    });
  },
  computed: {
    signInErrorMessage() {
      let errorMsg = '';
      switch (this.signInErrorKey.toLowerCase()) {
        case 'invalid_credentials':
          errorMsg = 'Invalid Username/Password credentials';
          break;
        case 'rejected':
          errorMsg = 'Your account has been rejected by ad administration';
          break;
        case 'inactive':
          errorMsg = 'Your account is inactive and cannot use to login anymore';
          break;
        case 'under_review':
          errorMsg = 'Your account is currently pending and under review.';
          break;
        default:
          errorMsg = 'Error occurred, please try to login again.';
          break;
      }

      return errorMsg;
    },
    isHudsonAcademy() {
      return window.location.host == 'thehudsonacademy.com';
    },
    managersList() {
      if (
        this.signUpForm.dealer == null ||
        _isString(this.signUpForm.dealer.id)
      )
        return null;

      const lists = [];
      _each(this.signUpForm.dealer.managers, (manager) => {
        lists.push({
          name: manager.name,
          id: manager.id,
        });
      });
      return lists;
    },
  },
  methods: {
    signIn() {
      this.$store.commit('dealerOptions/setOptions', []);
      this.showLoader(true);
      this.signInError = false;
      this.setOptions();
      this.$auth
        .login({
          email: this.email,
          password: this.password,
        })
        .then(({ data }) => {
          this.showLoader(false);
          this.$http.defaults.headers.common.Authorization = `Bearer ${data.access_token}`;
        })
        .catch((err) => {
          this.showLoader(false);
          this.signInError = true;
          this.signInErrorKey = err.response.data.error;
        });
    },
    setOptions() {
      this.$store
        .dispatch('dealerOptions/getOptions', this.email)
        .then(({ data }) => {
          this.$store.commit('dealerOptions/setOptions', data);
        });
    },
    showTab(type) {
      this.signInTab = false;
      this.signUpTab = false;
      this.forgetPasswordTab = false;
      this.resetPasswordTab = false;
      this[type] = true;
    },
    resetPassword() {
      this.loader = true;
      this.$http
        .post('/reset/password/', {
          token: this.$route.params.token,
          email: this.resetPasswordForm.email,
          password: this.resetPasswordForm.password,
          password_confirmation: this.resetPasswordForm.password_confirmation,
        })
        .then(
          (result) => {
            this.$notify('success', result.data.message);
          },
          (error) => {
            this.$notify('error', error.response.data.message);
          }
        );
      this.loader = false;
    },
    // eslint-disable-next-line consistent-return
    submitSignUp() {
      this.loader = true;
      this.$validator.validate('signUpForm.*').then((result) => {
        if (result) {
          this.$http
            .post('/auth/register', this.signUpForm)
            .then(() => {
              // I just made this notifs line... Please feel free to edit it.
              this.$notify(
                'success',
                'Thanks for signing up! A notification has been sent to the Admin for your account approval. You will receive an email once your account has been approved.'
              );
              this.closeSignUp();
            })
            .catch((errors) => {
              const messages = errors.response.data.errors;
              _forEach(messages, (value, key) => {
                this.errors.add({
                  field: key,
                  msg: _head(value),
                });
              });
            })
            .finally(() => (this.loader = false));
        }
      });
    },
    showLoader(show) {
      this.loader = show;
    },
    closeSignUp() {
      this.signUpForm.name = '';
      this.signUpForm.email = '';
      this.signUpForm.dealer = null;
      this.signUpForm.manager = null;
      this.signUpForm.terms_and_conditions = false;
      this.signUpTab = false;
      this.resetPasswordTab = false;
      this.signInTab = true;
    },
    searchDealers(query) {
      if (query == null || query.length <= 2) {
        this.dealersList = [];
        return;
      }

      this.dealersList = this.placeHolderForDealersList;
    },
    addNewDealer(newDealer) {
      const dealer = {
        id: newDealer,
        name: newDealer,
      };

      this.placeHolderForDealersList.push(dealer);
      this.dealersList.push(dealer);
      this.signUpForm.dealer = dealer;
    },
    submitForgotPasswordRequest() {
      const email = this.emailOfForgottenAccountPassword;
      this.$http
        .post('reset-password', {
          email,
        })
        .then((response) => {
          this.$notify('success', response.data.message);
        })
        .catch((err) => {
          const msg = err.response.data.message
            ? err.response.data.message
            : 'We could not find your account with that information. Please click the sign up link below to create a new one.';
          this.$notify('error', msg);
        });
    },
  },
};
</script>

<style lang="stylus">
.is-invalid
  border 1px solid #fd397a !important

.is-valid
  border 1px solid #1dc9b7 !important

.registration-select .multiselect__tags
  border-radius 46px !important
  border none !important
  background rgba(3,3,3,0.4) !important
  height 46px !important
  color #ffffff !important

.registration-select .multiselect__input,
.registration-select .multiselect__single
  min-height 30px !important
  line-height 30px !important
  border-radius 46px !important
  border none !important
  background none
  color #ffffff !important

.registration-select .multiselect__input:hover,
.registration-select .multiselect__single:focus
  background none
  border none !important

.registration-select .multiselect__content-wrapper
  border-radius 0px !important
  border none !important

.registration-select .multiselect__single
  font-size 12px

.registration-select .multiselect__placeholder
  font-size 12px
  color #ffffff
  padding-top 5px
  padding-left 8px

.registration-select .v-input__control
  border-radius 46px
  background rgba(3,3,3,0.4) !important
  height 46px

.registration-select .v-text-field--outlined fieldset
  border none

.registration-select .v-text-field.v-text-field--enclosed .v-input__append-inner
  margin-top 7px

.registration-select .theme--dark.v-icon
  color #999999

.registration-select .theme--dark.v-label
  font-size 12px

.registration-select .v-text-field--outlined .v-label
  top 14px

.registration-select .v-input
  font-size 12px
</style>
