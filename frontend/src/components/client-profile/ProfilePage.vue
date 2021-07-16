<template>
  <div>
    <div class="row">
      <div class="col-lg-4">
        <div class="kt-portlet kt-portlet--height-fluid-">
          <div class="kt-portlet__head kt-portlet__head--noborder">
            <div class="kt-portlet__head-label">
              <h3 class="kt-portlet__head-title"></h3>
            </div>
          </div>
          <div class="kt-portlet__body kt-portlet__body--fit-y">
            <!--begin::Widget -->
            <div class="kt-widget kt-widget--user-profile-1">
              <div class="kt-widget__head">
                <div class="kt-widget__media">
                  <img :src="displayProfilePicture" alt="image" />
                  <br />
                  <v-btn small class="mt-3" @click="adjustImage">
                    Edit Profile Picture
                  </v-btn>
                </div>
                <div class="kt-widget__content">
                  <div class="kt-widget__section">
                    <a href="#" class="kt-widget__username">
                      {{ profile.name }}
                      <i class="flaticon2-correct kt-font-success"></i>
                    </a>
                    <span class="kt-widget__subtitle">
                      {{ profile.job_title }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="kt-widget__body">
                <div class="kt-widget__content">
                  <div class="kt-widget__info">
                    <span class="kt-widget__label">Email:</span>
                    <a href="#" class="kt-widget__data">{{ profile.email }}</a>
                  </div>
                  <div class="kt-widget__info">
                    <span class="kt-widget__label">Phone:</span>
                    <a href="#" class="kt-widget__data">{{
                      profile.phone_number
                    }}</a>
                  </div>
                  <div class="kt-widget__info">
                    <span class="kt-widget__label">Company:</span>
                    <span class="kt-widget__data">{{ profile.company }}</span>
                  </div>
                </div>
              </div>
            </div>
            <adjust-profile-picture
              v-model="editImage"
            ></adjust-profile-picture>
            <!--end::Widget -->
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="kt-portlet">
          <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
              <h3 class="kt-portlet__head-title">
                <i class="fal fa-user-circle"></i>
                Account Information
                <small>change your account information</small>
              </h3>
            </div>
          </div>
          <form class="kt-form kt-form--label-right">
            <div class="kt-portlet__body">
              <div class="kt-section kt-section--first">
                <div class="kt-section__body">
                  <v-card>
                    <v-card-text>
                      <v-container class="kt-form">
                        <!-- User's Info -->
                        <v-row>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field
                              label="Name"
                              v-model="profile.name"
                              v-validate="'required'"
                              name="name"
                              autocomplete="off"
                            >
                            </v-text-field>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('name')"
                            >
                              {{ errors.first('name') }}
                            </span>
                          </v-col>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field
                              label="Job Title"
                              v-model="profile.job_title"
                              autocomplete="off"
                            >
                            </v-text-field>
                          </v-col>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field
                              label="Email Address"
                              v-validate="'required|email'"
                              v-model="profile.email"
                              name="email"
                              autocomplete="off"
                            >
                            </v-text-field>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('email')"
                              autocomplete="off"
                            >
                              {{ errors.first('email') }}
                            </span>
                          </v-col>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field
                              label="Phone Number"
                              v-model="profile.phone_number"
                              autocomplete="off"
                            >
                            </v-text-field>
                          </v-col>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field
                              :append-icon="
                                showPassword ? 'fal fa-eye' : 'fal fa-eye-slash'
                              "
                              :type="showPassword ? 'text' : 'password'"
                              @click:append="showPassword = !showPassword"
                              label="Password"
                              name="password"
                              ref="password"
                              v-validate="'truthy:password_confirmation'"
                              v-model="profile.password"
                              autocomplete="off"
                            >
                            </v-text-field>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('password')"
                            >
                              {{ errors.first('password') }}
                            </span>
                          </v-col>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field
                              :append-icon="
                                showConfirmPassword
                                  ? 'fal fa-eye'
                                  : 'fal fa-eye-slash'
                              "
                              :type="showConfirmPassword ? 'text' : 'password'"
                              @click:append="
                                showConfirmPassword = !showConfirmPassword
                              "
                              v-model="profile.password_confirmation"
                              label="Confirm Password"
                              ref="password_confirmation"
                              name="password_confirmation"
                              v-validate="'confirmed:password'"
                              data-vv-as="password"
                              autocomplete="off"
                            >
                            </v-text-field>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('password_confirmation')"
                            >
                              {{ errors.first('password_confirmation') }}
                            </span>
                          </v-col>

                          <v-col cols="12" sm="6" md="6">
                            <v-switch
                              v-model="profile.sms_notification"
                              class="ma-2"
                              label="SMS Notification"
                              name="sms_notification"
                            ></v-switch>
                          </v-col>

                          <v-col cols="12" sm="6" md="6">
                            <v-switch
                              v-model="profile.mail_subscription"
                              class="ma-2"
                              label="Email Subscription"
                              name="mail_subscription"
                            ></v-switch>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="updateProfile">
                        Save
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <lms-summary-report :userId="$auth.user().id"></lms-summary-report>
      </div>
    </div>
  </div>
</template>
<script>
import { head as _head } from 'lodash-es';
import LmsSummaryReport from '../client-reports/LmsSummaryReport';
import AdjustProfilePicture from './AdjustProfilePicture';
import '../../plugins/validator';

export default {
  name: 'ProfilePage',

  components: {
    AdjustProfilePicture,
    LmsSummaryReport,
  },

  data() {
    return {
      showConfirmPassword: false,
      showPassword: false,
      profile: {
        name: '',
        job_title: '',
        email: '',
        phone_number: '',
        company: '',
        profile_picture: null,
        password_confirmation: '',
        password: '',
        sms_notification: '',
        mail_subscription: '',
      },
      editImage: false,
    };
  },
  computed: {
    displayProfilePicture() {
      return this.currentProfile.profilePicture
        ? `https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/users/${this.currentProfile.id}/${this.currentProfile.profilePicture}`
        : 'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/default_profile_picture.jpg';
    },
    currentProfile() {
      return this.$store.state.profile.value;
    },
    userCompany() {
      return this.$store.state.profile.company;
    },
  },
  created() {
    this.$auth.fetchUser();
    this.setProfile(this.$auth.user());
    this.profile = {
      name: this.currentProfile.name,
      job_title: this.currentProfile.jobTitle,
      email: this.currentProfile.email,
      phone_number: this.currentProfile.phone,
      company: this.userCompany.name,
      password_confirmation: '',
      password: '',
      sms_notification: this.currentProfile.sms_notification,
      mail_subscription: this.currentProfile.mail_subscription,
    };
  },
  methods: {
    setProfile(profile) {
      this.$store.commit('profile/setProfile', {
        id: profile.id,
        name: profile.name,
        email: profile.email,
        profilePicture: profile.profile_picture,
        jobTitle: profile.job_title,
        phone: profile.phone_number,
        sms_notification: profile.sms_notification,
        mail_subscription: profile.mail_subscription,
      });
      this.$store.commit('profile/setCompany', profile.dealer);
    },
    updateProfile() {
      this.$validator.validate('*').then((result) => {
        if (result) {
          return this.update();
        }
      });
    },
    formData() {
      const newForm = new FormData();
      newForm.append('name', this.profile.name);
      newForm.append('email', this.profile.email);
      newForm.append('phone_number', this.profile.phone_number);
      newForm.append('job_title', this.profile.job_title);
      if (this.profile.password && this.profile.password_confirmation) {
        newForm.append('password', this.profile.password);
      }
      newForm.append('sms_notification', this.profile.sms_notification);
      newForm.append('mail_subscription', this.profile.mail_subscription);
      newForm.append('_method', 'patch');
      return newForm;
    },
    update() {
      this.axios
        .post(`/my-profile/${this.$auth.user().id}`, this.formData(), {
          headers: {
            'Content-type': 'multipart/form-data',
          },
        })
        .then(({ data }) => {
          this.profile.password = null;
          this.profile.password_confirmation = null;
          this.$validator.reset();
          this.setProfile(data);
          this.$notify(
            'success',
            'Your profile has been successfully updated!'
          );
        });
    },
    adjustImage() {
      this.editImage = true;
    },
  },
};
</script>
