<template>
  <div class="kt-portlet kt-portlet--height-fluid pt-2">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--breaker-sm flex-wrap"
    >
      <div class="kt-portlet__head-label mb-2">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-tasks-alt" />
        </span>
        <h3 class="kt-portlet__head-title">Manage Internet Shops</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
          <!--          <v-btn color="red darken-4" dark class="mb-2 mr-2" v-on="on">-->
          <!--            <i class="fal fa-plus mr-2" />-->
          <!--            Buy Numbers-->
          <!--          </v-btn>-->
          <!--          <v-dialog-->
          <!--            v-model="internetShopForm"-->
          <!--            hide-overlay-->
          <!--            transition="dialog-bottom-transition"-->
          <!--          >-->
          <!--            <template v-slot:activator="{ on }">-->
          <!--              <v-btn color="#005cb2" dark class="mb-2" v-on="on">-->
          <!--                <i class="fal fa-globe mr-2" />-->
          <!--                New Internet Shop-->
          <!--              </v-btn>-->
          <!--            </template>-->
          <!--            <v-card>-->
          <!--              <v-toolbar dark color="secondary">-->
          <!--                <v-card-title>-->
          <!--                  <i class="fal fa-globe" />-->

          <!--                  <span class="headline">{{ formTitle }}</span>-->
          <!--                </v-card-title>-->
          <!--                <v-spacer />-->
          <!--                <button-->
          <!--                  type="button"-->
          <!--                  class="btn btn-light btn-elevate-hover btn-circle btn-icon btn-sm"-->
          <!--                  @click="close"-->
          <!--                >-->
          <!--                  <i class="fal fa-times" />-->
          <!--                </button>-->
          <!--              </v-toolbar>-->
          <!--              <v-card-text>-->
          <!--                <v-container class="kt-form">-->
          <!--                  &lt;!&ndash; Start of Internet Shop &ndash;&gt;-->
          <!--                  <h5>Dealer's Info</h5>-->
          <!--                  <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-switch-->
          <!--                        v-model="isCompetitor"-->
          <!--                        label="is Competitor"-->
          <!--                        color="#f9b418"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('dealer.is_dealer')"-->
          <!--                        >{{ errors.first('dealer.is_dealer') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <vue-select-->
          <!--                        v-model="editedItem.dealer.id"-->
          <!--                        name="dealer.id"-->
          <!--                        :options="dealers"-->
          <!--                        :reduce="(dealer) => dealer.id"-->
          <!--                        v-validate="'required'"-->
          <!--                        label="name"-->
          <!--                      >-->
          <!--                      </vue-select>-->
          <!--                      <span-->
          <!--                        class="kt-font-primary"-->
          <!--                        v-if="-->
          <!--                          !editedItem.dealer.id && !errors.first('dealer.id')-->
          <!--                        "-->
          <!--                      >-->
          <!--                        Choose a company-->
          <!--                      </span>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('dealer.id')"-->
          <!--                        >{{ errors.first('dealer.id') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      &lt;!&ndash; <v-text-field-->
          <!--                        v-validate="'required'"-->
          <!--                        name="dealer.name"-->
          <!--                        v-model="editedItem.dealer.name"-->
          <!--                        label="Name of Specific Dealer"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field> &ndash;&gt;-->
          <!--                      <vue-select-->
          <!--                        v-model="editedItem.dealer.name"-->
          <!--                        name="dealer.name"-->
          <!--                        autocomplete="new-password"-->
          <!--                        v-validate="'required'"-->
          <!--                        taggable-->
          <!--                        :options="specificDealersResult"-->
          <!--                        :reduce="(dealer) => dealer.id"-->
          <!--                        :create-option="-->
          <!--                          (dealer) => ({ name: dealer, id: dealer })-->
          <!--                        "-->
          <!--                        @input="checkSpecificDealerDuplication"-->
          <!--                        label="name"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-primary"-->
          <!--                        v-if="-->
          <!--                          !editedItem.dealer.name &&-->
          <!--                          !errors.first('dealer.name')-->
          <!--                        "-->
          <!--                      >-->
          <!--                        Choose a specific dealer-->
          <!--                      </span>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('dealer.name')"-->
          <!--                        >{{ errors.first('dealer.name') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      &lt;!&ndash; <v-text-field-->
          <!--                        v-validate="'required'"-->
          <!--                        name="dealer.name"-->
          <!--                        v-model="editedItem.dealer.name"-->
          <!--                        label="Name of Specific Dealer"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field> &ndash;&gt;-->
          <!--                      <vue-select-->
          <!--                        v-model="editedItem.dealer.competitor"-->
          <!--                        name="dealer.competitor"-->
          <!--                        autocomplete="new-password"-->
          <!--                        v-validate="{-->
          <!--                          required: isCompetitor,-->
          <!--                        }"-->
          <!--                        taggable-->
          <!--                        :options="competitorsResult"-->
          <!--                        :reduce="(competitor) => competitor.id"-->
          <!--                        :create-option="-->
          <!--                          (competitor) => ({ name: competitor, id: competitor })-->
          <!--                        "-->
          <!--                        label="name"-->
          <!--                        :disabled="!isCompetitor"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-primary"-->
          <!--                        v-if="-->
          <!--                          !editedItem.dealer.competitor &&-->
          <!--                          !errors.first('dealer.competitor')-->
          <!--                        "-->
          <!--                      >-->
          <!--                        Choose a competitor-->
          <!--                      </span>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('dealer.competitor')"-->
          <!--                        >{{ errors.first('dealer.competitor') }}-->
          <!--                      </span>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-select-->
          <!--                        v-model="editedItem.dealer.timezone"-->
          <!--                        :items="['EST', 'CST', 'MST', 'PST', 'AST', 'HST']"-->
          <!--                        :menu-props="{ closeOnClick: true }"-->
          <!--                        label="Timezone"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="dealer.timezone"-->
          <!--                        autocomplete="new-password"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('dealer.timezone')"-->
          <!--                        >{{ errors.first('dealer.timezone') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-validate="'required'"-->
          <!--                        name="dealer.zipcode"-->
          <!--                        v-model="editedItem.dealer.zipcode"-->
          <!--                        label="Zip Code"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('dealer.zipcode')"-->
          <!--                        >{{ errors.first('dealer.zipcode') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      &lt;!&ndash; <v-autocomplete-->
          <!--                        v-model="editedItem.location.state"-->
          <!--                        :items="states"-->
          <!--                        prepend-icon="fal fa-route-interstate"-->
          <!--                        item-text="name"-->
          <!--                        item-value="id"-->
          <!--                        @change="setCities"-->
          <!--                        :menu-props="{ closeOnClick: true }"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="location.state"-->
          <!--                        autocomplete="new-password"-->
          <!--                      /> &ndash;&gt;-->
          <!--                      <vue-select-->
          <!--                        v-model="editedItem.location.state"-->
          <!--                        :options="states"-->
          <!--                        label="name"-->
          <!--                        :reduce="(state) => state.id"-->
          <!--                        @input="setCities"-->
          <!--                        autocomplete="new-password"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="location.state"-->
          <!--                      >-->
          <!--                      </vue-select>-->
          <!--                      <span-->
          <!--                        class="kt-font-primary"-->
          <!--                        v-if="-->
          <!--                          !editedItem.location.state &&-->
          <!--                          !errors.first('location.state')-->
          <!--                        "-->
          <!--                      >-->
          <!--                        Choose a state-->
          <!--                      </span>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('location.state')"-->
          <!--                        >{{ errors.first('location.state') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->

          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      &lt;!&ndash; <v-autocomplete-->
          <!--                        v-model="editedItem.location.city"-->
          <!--                        :items="citiesList"-->
          <!--                        prepend-icon="fal fa-city"-->
          <!--                        v-validate="'required'"-->
          <!--                        item-text="value"-->
          <!--                        item-value="id"-->
          <!--                        :menu-props="{ closeOnClick: true }"-->
          <!--                        name="location.city"-->
          <!--                        autocomplete="new-password"-->
          <!--                      /> &ndash;&gt;-->
          <!--                      <vue-select-->
          <!--                        v-model="editedItem.location.city"-->
          <!--                        :options="cities"-->
          <!--                        label="value"-->
          <!--                        :reduce="(city) => city.id"-->
          <!--                        autocomplete="new-password"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="location.city"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('location.city')"-->
          <!--                        >{{ errors.first('location.city') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                  </v-row>-->
          <!--                  <h5>Shop's Info</h5>-->
          <!--                  <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-radio-group-->
          <!--                        v-model="editedItem.shop.is_new"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="shop.is_new"-->
          <!--                        row-->
          <!--                      >-->
          <!--                        <v-radio-->
          <!--                          label="New"-->
          <!--                          value="1"-->
          <!--                          color="yellow darken-3"-->
          <!--                        ></v-radio>-->
          <!--                        <v-radio-->
          <!--                          label="Used"-->
          <!--                          value="0"-->
          <!--                          color="yellow darken-3"-->
          <!--                        ></v-radio>-->
          <!--                      </v-radio-group>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('shop.is_new')"-->
          <!--                        >{{ errors.first('shop.is_new') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->

          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      &lt;!&ndash; <v-autocomplete-->
          <!--                        v-model="editedItem.lead.source"-->
          <!--                        :items="leadsources"-->
          <!--                        label="Select Lead Source(enter to create)"-->
          <!--                        prepend-icon="fal fa-clipboard-user"-->
          <!--                        item-text="value"-->
          <!--                        :menu-props="{ closeOnClick: true }"-->
          <!--                        item-value="id"-->
          <!--                        v-validate="'required'"-->
          <!--                        chips-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-autocomplete> &ndash;&gt;-->
          <!--                      <vue-select-->
          <!--                        v-model="editedItem.lead.source"-->
          <!--                        :options="leadsources"-->
          <!--                        label="value"-->
          <!--                        :reduce="(source) => source.id"-->
          <!--                        autocomplete="new-password"-->
          <!--                        v-validate="'required'"-->
          <!--                        @blur="blurItem()"-->
          <!--                        @input="focusItem()"-->
          <!--                        taggable-->
          <!--                        push-tags-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-primary"-->
          <!--                        v-if="-->
          <!--                          !editedItem.lead.source &&-->
          <!--                          !errors.first('lead.source')-->
          <!--                        "-->
          <!--                      >-->
          <!--                        Select a lead source-->
          <!--                      </span>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('lead.source')"-->
          <!--                        >{{ errors.first('lead.source') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->

          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-validate="'required'"-->
          <!--                        name="shop.sourceLink"-->
          <!--                        v-model="editedItem.shop.sourceLink"-->
          <!--                        label="Source's Link"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('shop.sourceLink')"-->
          <!--                        >{{ errors.first('shop.sourceLink') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-validate="'required'"-->
          <!--                        name="car.vin"-->
          <!--                        @blur="fetchMakeAndModel"-->
          <!--                        v-model="editedItem.car.vin"-->
          <!--                        label="Enter VIN"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('car.vin')"-->
          <!--                        >{{ errors.first('car.vin') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.car.make"-->
          <!--                        :loading="vinLoading"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="car.make"-->
          <!--                        label="Make of the Car"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('car.make')"-->
          <!--                        >{{ errors.first('car.make') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.car.model"-->
          <!--                        :loading="vinLoading"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="car.model"-->
          <!--                        label="Model of the Car"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('car.model')"-->
          <!--                        >{{ errors.first('car.model') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.lead.name"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="lead.name"-->
          <!--                        label="Lead's Name"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('lead.name')"-->
          <!--                        >{{ errors.first('lead.name') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-validate="'required'"-->
          <!--                        name="lead.email"-->
          <!--                        v-model="editedItem.lead.email"-->
          <!--                        label="Lead's Email"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('lead.email')"-->
          <!--                        >{{ errors.first('lead.email') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      &lt;!&ndash; <v-autocomplete-->
          <!--                        v-model="editedItem.lead.phoneNumber"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="lead.phoneNumber"-->
          <!--                        :items="numberList"-->
          <!--                        :allow-overflow="true"-->
          <!--                        @blur="fetchTwilioData"-->
          <!--                        :menu-props="{ closeOnClick: true }"-->
          <!--                        label="Lead's Phone Number"-->
          <!--                        autocomplete="new-password"-->
          <!--                      /> &ndash;&gt;-->
          <!--                      <vue-select-->
          <!--                        v-model="editedItem.lead.phoneNumber"-->
          <!--                        @search="searchPhoneNumber"-->
          <!--                        :options="phoneNumbersSearchResult"-->
          <!--                        autocomplete="new-password"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="lead.phoneNumber"-->
          <!--                        label="value"-->
          <!--                        :reduce="(number) => number.value"-->
          <!--                        @input="fetchTwilioData"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-primary"-->
          <!--                        v-if="-->
          <!--                          !editedItem.lead.phoneNumber &&-->
          <!--                          !errors.first('lead.phoneNumber')-->
          <!--                        "-->
          <!--                      >-->
          <!--                        Lead's Phone Number-->
          <!--                      </span>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('lead.phoneNumber')"-->
          <!--                        >{{ errors.first('lead.phoneNumber') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-validate="'required'"-->
          <!--                        name="shop.salesPersonName"-->
          <!--                        v-model="editedItem.shop.salesPersonName"-->
          <!--                        label="Salesperson's Name"-->
          <!--                        autocomplete="new-password"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('shop.salesPersonName')"-->
          <!--                        >{{ errors.first('shop.salesPersonName') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        name="shop.csmName"-->
          <!--                        v-model="editedItem.shop.csmName"-->
          <!--                        label="CSM's Name"-->
          <!--                        messages="For True Car shops only"-->
          <!--                        autocomplete="new-password"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('shop.csmName')"-->
          <!--                        >{{ errors.first('shop.csmName') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.shop.callGuideLink"-->
          <!--                        messages="Optional"-->
          <!--                        label="Call Guide Link"-->
          <!--                        autocomplete="new-password"-->
          <!--                      />-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-datetime-picker-->
          <!--                        v-validate="'required'"-->
          <!--                        name="shop.startAt"-->
          <!--                        label="Start Date and Time"-->
          <!--                        v-model="editedItem.shop.startAt"-->
          <!--                        @input="calculateCallResponseTime"-->
          <!--                        okText="SET"-->
          <!--                        dateIcon="fal fa-calendar-alt"-->
          <!--                        timeIcon="fal fa-clock"-->
          <!--                        date-format="MM-dd-yyyy"-->
          <!--                        time-format="hh:mm a"-->
          <!--                        ref="startDateAndTime"-->
          <!--                      >-->
          <!--                        <template slot="dateIcon">-->
          <!--                          <i class="fal fa-calendar-alt"></i>-->
          <!--                        </template>-->
          <!--                        <template slot="timeIcon">-->
          <!--                          <i class="fal fa-clock"></i>-->
          <!--                        </template>-->
          <!--                      </v-datetime-picker>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('shop.startAt')"-->
          <!--                        >{{ errors.first('shop.startAt') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.shop.duration"-->
          <!--                        v-validate="'required'"-->
          <!--                        name="shop.duration"-->
          <!--                        label="Shop Duration"-->
          <!--                        autocomplete="new-password"-->
          <!--                      ></v-text-field>-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('shop.duration')"-->
          <!--                        >{{ errors.first('shop.duration') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-file-input-->
          <!--                        accept="image/png, image/jpeg, image/bmp"-->
          <!--                        label="Verification Screenshot"-->
          <!--                        v-validate="{-->
          <!--                          ext: ['png', 'jpg'],-->
          <!--                          required: this.editedIndex == -1 ? true : false,-->
          <!--                        }"-->
          <!--                        ref="verificationScreenshot"-->
          <!--                        v-model="verificationScreenshot"-->
          <!--                        name="verificationScreenshot"-->
          <!--                      />-->
          <!--                      <span-->
          <!--                        class="kt-font-danger validate-error"-->
          <!--                        v-if="errors.first('verificationScreenshot')"-->
          <!--                        >{{ errors.first('verificationScreenshot') }}</span-->
          <!--                      >-->
          <!--                    </v-col>-->
          <!--                  </v-row>-->
          <!--                  <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="3">-->
          <!--                      <v-select-->
          <!--                        v-model="editedItem.shop.secretShopperId"-->
          <!--                        :items="secretShopperList"-->
          <!--                        label="Secret Shopper"-->
          <!--                        item-text="name"-->
          <!--                        item-value="id"-->
          <!--                      ></v-select>-->
          <!--                    </v-col>-->
          <!--                    <v-col-->
          <!--                      cols="12"-->
          <!--                      sm="12"-->
          <!--                      md="3"-->
          <!--                      v-if="editedIndex != -1 && $auth.check()"-->
          <!--                    >-->
          <!--                      <v-btn @click="fetchTwilioSmsData">Fetch SMS Data</v-btn>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="6">-->
          <!--                      <h6 class="text-center">-->
          <!--                        Current Verification Screenshot-->
          <!--                      </h6>-->
          <!--                      <p class="text-center font-weight-medium">-->
          <!--                        Click the image to see full size.-->
          <!--                      </p>-->
          <!--                      <a-->
          <!--                        :href="`${aws_url}verification-screenshots/${editedItem.verification_screenshot}`"-->
          <!--                        target="_blank"-->
          <!--                        class="text-center"-->
          <!--                      >-->
          <!--                        <v-img-->
          <!--                          aspect-ratio="2"-->
          <!--                          :src="`${aws_url}verification-screenshots/${editedItem.verification_screenshot}`"-->
          <!--                        ></v-img>-->
          <!--                      </a>-->
          <!--                    </v-col>-->
          <!--                  </v-row>-->
          <!--                  <h5>Call Data</h5>-->
          <!--                  <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-datetime-picker-->
          <!--                        label="First Call Received"-->
          <!--                        v-model="editedItem.call.first.received"-->
          <!--                        dateIcon="fal fa-calendar-alt"-->
          <!--                        timeIcon="fal fa-clock"-->
          <!--                        date-format="MM-dd-yyyy"-->
          <!--                        time-format="hh:mm a"-->
          <!--                        :loading="twilioLoading"-->
          <!--                        ref="firstCallReceived"-->
          <!--                      >-->
          <!--                        <template slot="dateIcon">-->
          <!--                          <i class="fal fa-calendar-alt" />-->
          <!--                        </template>-->
          <!--                        <template slot="timeIcon">-->
          <!--                          <i class="fal fa-clock" />-->
          <!--                        </template>-->
          <!--                      </v-datetime-picker>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.call.first.response"-->
          <!--                        label="Call Response Time"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.call.attempts"-->
          <!--                        :loading="twilioLoading"-->
          <!--                        label="Call Attempts"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                  </v-row>-->
          <!--                  <h5>SMS Data</h5>-->
          <!--                  <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-datetime-picker-->
          <!--                        label="First SMS Received"-->
          <!--                        v-model="editedItem.sms.first.received"-->
          <!--                        dateIcon="fal fa-calendar-alt"-->
          <!--                        timeIcon="fal fa-clock"-->
          <!--                        date-format="MM-dd-yyyy"-->
          <!--                        time-format="hh:mm a"-->
          <!--                        :loading="twilioLoading"-->
          <!--                        ref="firstSmsReceived"-->
          <!--                      >-->
          <!--                        <template slot="dateIcon">-->
          <!--                          <i class="fal fa-calendar-alt" />-->
          <!--                        </template>-->
          <!--                        <template slot="timeIcon">-->
          <!--                          <i class="fal fa-clock" />-->
          <!--                        </template>-->
          <!--                      </v-datetime-picker>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.sms.first.response"-->
          <!--                        label="SMS Response Time"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.sms.attempts"-->
          <!--                        :loading="twilioLoading"-->
          <!--                        label="SMS Attempts"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                  </v-row>-->
          <!--                  <h5>Email Data</h5>-->
          <!--                  <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-datetime-picker-->
          <!--                        label="First Email Received"-->
          <!--                        v-model="editedItem.email.first.received"-->
          <!--                        dateIcon="fal fa-calendar-alt"-->
          <!--                        @input="calculateCallResponseTime"-->
          <!--                        timeIcon="fal fa-clock"-->
          <!--                        date-format="MM-dd-yyyy"-->
          <!--                        time-format="hh:mm a"-->
          <!--                        ref="firstEmailReceived"-->
          <!--                      >-->
          <!--                        <template slot="dateIcon">-->
          <!--                          <i class="fal fa-calendar-alt" />-->
          <!--                        </template>-->
          <!--                        <template slot="timeIcon">-->
          <!--                          <i class="fal fa-clock" />-->
          <!--                        </template>-->
          <!--                      </v-datetime-picker>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.email.first.response"-->
          <!--                        label="Email Response Time"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.email.attempts"-->
          <!--                        label="Email Attempts"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                  </v-row>-->
          <!--                  &lt;!&ndash; <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="6">-->
          <!--                      <v-datetime-picker-->
          <!--                        label="Second Email Received (For True Car)"-->
          <!--                        v-model="editedItem.email.second.received"-->
          <!--                        dateIcon="fal fa-calendar-alt"-->
          <!--                        timeIcon="fal fa-clock"-->
          <!--                        date-format="MM-dd-yyyy"-->
          <!--                        time-format="hh:mm a"-->
          <!--                        ref="secondEmailReceived"-->
          <!--                      >-->
          <!--                        <template slot="dateIcon">-->
          <!--                          <i class="fal fa-calendar-alt" />-->
          <!--                        </template>-->
          <!--                        <template slot="timeIcon">-->
          <!--                          <i class="fal fa-clock" />-->
          <!--                        </template>-->
          <!--                      </v-datetime-picker>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="6">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.email.second.response"-->
          <!--                        label="Second Email Response Time"-->
          <!--                        messages="For True Car shops only"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                  </v-row> &ndash;&gt;-->
          <!--                  &lt;!&ndash; <h5>Chat Data</h5>-->
          <!--                  <v-row>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-datetime-picker-->
          <!--                        label="First Chat Received"-->
          <!--                        v-model="editedItem.chat.first.received"-->
          <!--                        @input="calculateCallResponseTime"-->
          <!--                        dateIcon="fal fa-calendar-alt"-->
          <!--                        timeIcon="fal fa-clock"-->
          <!--                        date-format="MM-dd-yyyy"-->
          <!--                        time-format="hh:mm a"-->
          <!--                        ref="firstChatReceived"-->
          <!--                      >-->
          <!--                        <template slot="dateIcon">-->
          <!--                          <i class="fal fa-calendar-alt" />-->
          <!--                        </template>-->
          <!--                        <template slot="timeIcon">-->
          <!--                          <i class="fal fa-clock" />-->
          <!--                        </template>-->
          <!--                      </v-datetime-picker>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.chat.first.response"-->
          <!--                        label="Chat Response Time"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                    <v-col cols="12" sm="12" md="4">-->
          <!--                      <v-text-field-->
          <!--                        v-model="editedItem.chat.attempts"-->
          <!--                        label="Chat Attempts"-->
          <!--                      ></v-text-field>-->
          <!--                    </v-col>-->
          <!--                  </v-row> &ndash;&gt;-->
          <!--                  <h5>Email Screenshots</h5>-->
          <!--                  <v-row>-->
          <!--                    <draggable-->
          <!--                      v-model="temp.emailScreenshots"-->
          <!--                      style="display: contents"-->
          <!--                      @end="emailOrderChanged"-->
          <!--                    >-->
          <!--                      <v-col-->
          <!--                        v-for="(screenshot, index) in temp.emailScreenshots"-->
          <!--                        v-bind:key="index"-->
          <!--                      >-->
          <!--                        <v-card class="emailItems" dense width="400" shaped>-->
          <!--                          <v-card-actions>-->
          <!--                            <h4>{{ index + 1 }})</h4>-->

          <!--                            <v-spacer />-->
          <!--                            <v-badge-->
          <!--                              :content="index"-->
          <!--                              :value="index"-->
          <!--                              color="green"-->
          <!--                              overlap-->
          <!--                            >-->
          <!--                              <v-btn-->
          <!--                                icon-->
          <!--                                large-->
          <!--                                @click="-->
          <!--                                  removeImage(-->
          <!--                                    index,-->
          <!--                                    preview.email_screenshots,-->
          <!--                                    temp.emailScreenshots-->
          <!--                                  )-->
          <!--                                "-->
          <!--                              >-->
          <!--                                <v-icon>fa-times-square</v-icon>-->
          <!--                              </v-btn>-->
          <!--                            </v-badge>-->
          <!--                          </v-card-actions>-->
          <!--                          <v-img>-->
          <!--                            <picture>-->
          <!--                              <img-->
          <!--                                :src="-->
          <!--                                  'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/' +-->
          <!--                                  editedIndex +-->
          <!--                                  '/email/' +-->
          <!--                                  screenshot.baseName-->
          <!--                                "-->
          <!--                                alt="Email Screenshot"-->
          <!--                                width="400"-->
          <!--                                height="225"-->
          <!--                              />-->
          <!--                            </picture>-->
          <!--                          </v-img>-->
          <!--                        </v-card>-->
          <!--                      </v-col>-->
          <!--                    </draggable>-->
          <!--                  </v-row>-->

          <!--                  <v-row>-->
          <!--                    <draggable-->
          <!--                      v-model="emailThumb"-->
          <!--                      style="display: contents"-->
          <!--                      @end="emailOrderChanged"-->
          <!--                    >-->
          <!--                      <v-col-->
          <!--                        v-for="(screenshot, index) in emailThumb"-->
          <!--                        v-bind:key="index"-->
          <!--                      >-->
          <!--                        <v-card class="emailItems" dense width="400" shaped>-->
          <!--                          <v-card-actions>-->
          <!--                            <h4>{{ index + 1 }}). {{ screenshot.name }}</h4>-->

          <!--                            <v-spacer />-->
          <!--                            <v-badge-->
          <!--                              :content="index"-->
          <!--                              :value="index"-->
          <!--                              color="green"-->
          <!--                              overlap-->
          <!--                            >-->
          <!--                              <v-btn-->
          <!--                                icon-->
          <!--                                large-->
          <!--                                @click="-->
          <!--                                  removeImage(-->
          <!--                                    index,-->
          <!--                                    preview.email_screenshots,-->
          <!--                                    emailThumb-->
          <!--                                  )-->
          <!--                                "-->
          <!--                              >-->
          <!--                                <v-icon>fa-times-square</v-icon>-->
          <!--                              </v-btn>-->
          <!--                            </v-badge>-->
          <!--                          </v-card-actions>-->
          <!--                          <v-img>-->
          <!--                            <picture>-->
          <!--                              <source-->
          <!--                                v-if="screenshot.old"-->
          <!--                                :srcset="screenshot.webp"-->
          <!--                                type="image/webp"-->
          <!--                              />-->
          <!--                              <source-->
          <!--                                v-if="screenshot.old"-->
          <!--                                :srcset="screenshot.jpg"-->
          <!--                                type="image/jpeg"-->
          <!--                              />-->
          <!--                              <img-->
          <!--                                v-if="screenshot.old"-->
          <!--                                :src="screenshot.jpg"-->
          <!--                                alt="Email Screenshot"-->
          <!--                              />-->
          <!--                              <img-->
          <!--                                v-if="!screenshot.old"-->
          <!--                                :src="screenshot.img64"-->
          <!--                                alt="Email Screenshot"-->
          <!--                                width="400"-->
          <!--                                height="225"-->
          <!--                              />-->
          <!--                            </picture>-->
          <!--                          </v-img>-->
          <!--                        </v-card>-->
          <!--                      </v-col>-->
          <!--                    </draggable>-->
          <!--                  </v-row>-->
          <!--                  &lt;!&ndash; Need to Upload Multiple files with Preview &ndash;&gt;-->
          <!--                  <v-file-input-->
          <!--                    v-model="preview.email_screenshots"-->
          <!--                    placeholder="Upload your Email Screenshots"-->
          <!--                    label="Email Screenshots(multiple allowed)"-->
          <!--                    multiple-->
          <!--                    prepend-icon="mdi-camera"-->
          <!--                    accept="image/*"-->
          <!--                    @blur="emailScreenshotBlur"-->
          <!--                  >-->
          <!--                    <template v-slot:selection="{ text }">-->
          <!--                      <v-chip small label color="primary">{{ text }}</v-chip>-->
          <!--                    </template>-->
          <!--                  </v-file-input>-->

          <!--                  &lt;!&ndash; <h5>Chat Screenshots</h5>-->
          <!--                  <v-row>-->
          <!--                    <draggable-->
          <!--                      v-model="temp.chat_screenshots"-->
          <!--                      style="display: contents"-->
          <!--                      @end="emailOrderChanged"-->
          <!--                    >-->
          <!--                      <v-col-->
          <!--                        v-for="(screenshot, index) in temp.chat_screenshots"-->
          <!--                        v-bind:key="index"-->
          <!--                      >-->
          <!--                        <v-card dense width="400" shaped>-->
          <!--                          <v-card-actions>-->
          <!--                            <h4>{{ index + 1 }}). {{ screenshot.name }}</h4>-->
          <!--                            <v-spacer />-->
          <!--                            <v-badge-->
          <!--                              :content="index"-->
          <!--                              :value="index"-->
          <!--                              color="green"-->
          <!--                              overlap-->
          <!--                            >-->
          <!--                              <v-btn-->
          <!--                                icon-->
          <!--                                large-->
          <!--                                @click="-->
          <!--                                  removeImage(-->
          <!--                                    index,-->
          <!--                                    preview.chat_screenshots,-->
          <!--                                    temp.chat_screenshots-->
          <!--                                  )-->
          <!--                                "-->
          <!--                              >-->
          <!--                                <v-icon>fa-times-square</v-icon>-->
          <!--                              </v-btn>-->
          <!--                            </v-badge>-->
          <!--                          </v-card-actions>-->
          <!--                          <v-img>-->
          <!--                            <picture>-->
          <!--                              <img-->
          <!--                                :src="-->
          <!--                                  'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/' +-->
          <!--                                  editedIndex +-->
          <!--                                  '/chat/' +-->
          <!--                                  screenshot.baseName-->
          <!--                                "-->
          <!--                                alt="Chat Screenshot"-->
          <!--                                width="400"-->
          <!--                                height="225"-->
          <!--                              />-->
          <!--                            </picture>-->
          <!--                          </v-img>-->
          <!--                        </v-card>-->
          <!--                      </v-col>-->
          <!--                    </draggable>-->
          <!--                  </v-row> &ndash;&gt;-->
          <!--                  &lt;!&ndash; <v-row>-->
          <!--                    <draggable-->
          <!--                      v-model="chatThumb"-->
          <!--                      style="display: contents"-->
          <!--                      @end="emailOrderChanged"-->
          <!--                    >-->
          <!--                      <v-col-->
          <!--                        v-for="(screenshot, index) in chatThumb"-->
          <!--                        v-bind:key="index"-->
          <!--                      >-->
          <!--                        <v-card dense width="400" shaped>-->
          <!--                          <v-card-actions>-->
          <!--                            <h4>{{ index + 1 }}). {{ screenshot.name }}</h4>-->
          <!--                            <v-spacer />-->
          <!--                            <v-badge-->
          <!--                              :content="index"-->
          <!--                              :value="index"-->
          <!--                              color="green"-->
          <!--                              overlap-->
          <!--                            >-->
          <!--                              <v-btn-->
          <!--                                icon-->
          <!--                                large-->
          <!--                                @click="-->
          <!--                                  removeImage(-->
          <!--                                    index,-->
          <!--                                    preview.chat_screenshots,-->
          <!--                                    chatThumb-->
          <!--                                  )-->
          <!--                                "-->
          <!--                              >-->
          <!--                                <v-icon>fa-times-square</v-icon>-->
          <!--                              </v-btn>-->
          <!--                            </v-badge>-->
          <!--                          </v-card-actions>-->
          <!--                          <v-img>-->
          <!--                            <picture>-->
          <!--                              <source-->
          <!--                                v-if="screenshot.old"-->
          <!--                                :srcset="screenshot.webp"-->
          <!--                                type="image/webp"-->
          <!--                              />-->
          <!--                              <source-->
          <!--                                v-if="screenshot.old"-->
          <!--                                :srcset="screenshot.jpg"-->
          <!--                                type="image/jpeg"-->
          <!--                              />-->
          <!--                              <img-->
          <!--                                v-if="screenshot.old"-->
          <!--                                :src="screenshot.jpg"-->
          <!--                                alt="Chat Screenshot"-->
          <!--                              />-->
          <!--                              <img-->
          <!--                                v-if="!screenshot.old"-->
          <!--                                :src="screenshot.img64"-->
          <!--                                alt="Chat Screenshot"-->
          <!--                                width="400"-->
          <!--                                height="225"-->
          <!--                              />-->
          <!--                            </picture>-->
          <!--                          </v-img>-->
          <!--                        </v-card>-->
          <!--                      </v-col>-->
          <!--                    </draggable>-->
          <!--                  </v-row> &ndash;&gt;-->
          <!--                  &lt;!&ndash; Need to Upload Multiple files with Preview &ndash;&gt;-->
          <!--                  &lt;!&ndash; <v-file-input-->
          <!--                    v-model="preview.chat_screenshots"-->
          <!--                    placeholder="Upload your Chat Screenshots"-->
          <!--                    label="Chat Screenshots(multiple allowed)"-->
          <!--                    multiple-->
          <!--                    prepend-icon="mdi-camera"-->
          <!--                    accept="image/*"-->
          <!--                    @blur="chatScreenshotBlur"-->
          <!--                  >-->
          <!--                    <template v-slot:selection="{ text }">-->
          <!--                      <v-chip small label color="primary">{{ text }}</v-chip>-->
          <!--                    </template>-->
          <!--                  </v-file-input>-->
          <!--                  <v-row>-->
          <!--                    <v-col-->
          <!--                      v-if="editedIndex === -1"-->
          <!--                      v-for="(screenshot, index) in chatScreenshotsPreview"-->
          <!--                      v-bind:key="index"-->
          <!--                    >-->
          <!--                      <img :src="screenshot" width="300" height="250" />-->
          <!--                    </v-col>-->
          <!--                    <v-col v-if="editedIndex !== -1"></v-col>-->
          <!--                  </v-row> &ndash;&gt;-->
          <!--                  &lt;!&ndash; <vue-repeater ref="chatScreenshot" v-model="chatScreenshot" /> &ndash;&gt;-->
          <!--                  &lt;!&ndash; End of Internet Shop &ndash;&gt;-->
          <!--                </v-container>-->
          <!--              </v-card-text>-->

          <!--              <v-card-actions>-->
          <!--                <v-spacer />-->
          <!--                <v-btn color="blue-grey darken-1" text @click="close"-->
          <!--                  >Cancel</v-btn-->
          <!--                >-->
          <!--                <v-btn-->
          <!--                  color="blue darken-1"-->
          <!--                  text-->
          <!--                  :loading="processingData"-->
          <!--                  @click="save"-->
          <!--                  >Save</v-btn-->
          <!--                >-->
          <!--              </v-card-actions>-->
          <!--            </v-card>-->
          <!--          </v-dialog>-->
          <!--          <v-btn color="#1e88e5" dark class="mb-2 ml-2" v-on="on">-->
          <!--            <i class="fal fa-phone mr-2" />-->
          <!--            New Phone Shop-->
          <!--          </v-btn>-->
          <!--          <v-btn color="#6ab7ff" class="mb-2 ml-2" v-on="on">-->
          <!--            <i class="fal fa-layer-group mr-2" />-->
          <!--            New Group Shop-->
          <!--          </v-btn>-->
          <v-btn
            color="#1e88e5"
            dark
            class="mb-2 ml-2"
            to="/secretshop-management/internet-shop"
            target="_blank"
            text
          >
            <i class="fal fa-plus mr-2" />
            New Internet Shop
          </v-btn>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="shops"
        :server-items-length="pagination.total"
        :items-per-page="pagination.per_page"
        :loading="isFetchingData"
        sort-by="dealers"
        class="elevation-1"
        :options.sync="options"
      >
        <template v-slot:item.created_at="{ item }">
          <span>{{ item.created_at | displayDateTime }}</span>
        </template>
        <template v-slot:top>
          <div class="px-3">
            <div class="row no-gutters pt-3">
              <!-- <div class="col-xl-2">
                <v-select
                  class="mt-0 pt-0"
                  :loading="isFetchingRecords"
                  :disabled="isFetchingRecords"
                  @change="fetchData"
                  v-model="filters.status"
                  multiple
                  prepend-inner-icon="fas fa-filter"
                  label="Filter by Status"
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
              </div> -->
              <!--              <div class="col-xl-2 ml-2">
                <v-select
                  class="mt-0 pt-0"
                  prepend-inner-icon="fas fa-filter"
                  label="Select Filter"
                  :items="['Type', 'Secret Shopper', 'Status', 'Category']"
                >
                  <template v-slot:prepend-item>
                    <v-list-item ripple @click="toggleAllFilter">
                      <v-list-item-content>
                        <v-list-item-title>Select the Filter</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider class="mt-2"></v-divider>
                  </template>
                </v-select>
              </div>
              <div class="col-xl-3 ml-2">
                <v-select
                  class="mt-0 pt-0"
                  multiple
                  label="Select Value"
                  prepend-inner-icon="fas fa-filter"
                  :items="['Internet Shop', 'Phone Shop', 'Group Shop']"
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
              </div>-->
              <v-spacer />
              <div class="col-xl-4">
                <v-text-field
                  class="mt-0 pt-0"
                  v-model.trim="filters.search"
                  @change="fetchData"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
              </div>
            </div>
          </div>
        </template>
        <template v-slot:item.action="{ item }">
          <div v-if="item.deleted_at === null">
            <v-btn text @click="openShop(item)" color="blue darken-3" small>
              <v-icon small>fal fa-eye</v-icon>
            </v-btn>
            <v-btn text @click="editShop(item)" color="grey darken-3" small>
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
          <v-btn color="primary" @click="fetchData">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import {
  filter as _filter,
  find as _find,
  forEach as _forEach,
  head as _head,
  map as _map,
  size as _size,
} from 'lodash-es';
import draggable from 'vuedraggable';
import moment from 'moment';
import dayjs from 'dayjs';

function resetToDefaultValues() {
  return {
    dealer: {
      is_dealer: true,
      id: '',
      name: '',
      timezone: '',
      competitor: '',
      zipcode: '',
    },
    location: {
      state: '',
      city: '',
    },
    shop: {
      is_new: '',
      sourceLink: '',
      salesPersonName: '',
      csmName: '',
      callGuideLink: '',
      startAt: '',
      duration: '',
      secretShopperId: null,
    },
    lead: {
      source: 0,
      name: '',
      email: '',
      phoneNumber: '',
    },
    car: {
      vin: '',
      make: '',
      model: '',
    },
    call: {
      first: {
        received: null,
        response: '',
      },
      attempts: 0,
    },
    sms: {
      first: {
        received: null,
        response: '',
      },
      attempts: 0,
    },
    email: {
      first: {
        received: null,
        response: '',
      },
      second: {
        received: null,
        response: '',
      },
      attempts: 0,
    },
    chat: {
      first: {
        received: null,
        response: '',
      },
      attempts: 0,
    },
  };
}

export default {
  name: 'InternetShop',
  components: {
    draggable,
  },
  data: () => ({
    processingData: false,
    isFetchingRecords: false,
    emailThumb: [],
    chatThumb: [],
    filters: {
      search: '',
      status: '',
    },
    preview: {
      api: '/api/image?height=400&width=400&fileName=',
      chat_screenshots: [],
      email_screenshots: [],
    },
    temp: {
      emailScreenshots: [],
      chat_screenshots: [],
    },
    isFetchingData: false,
    emailScreenshotsPreview: [],
    chatScreenshotsPreview: [],
    pagination: {
      total: 1,
      per_page: 5,
      current_page: 1,
      sortBy: '',
      sortDesc: false,
    },
    options: {},
    numberDefault: [
      {
        text: '+15033869622',
        value: '+15033869622',
      },
      {
        text: 'Working Phone 1',
        value: '+16824782690',
      },
      {
        text: '+15037414894',
        value: '+15037414894',
      },
      {
        text: '+15037147677',
        value: '+15037147677',
      },
    ],
    vinLoading: false,
    twilioLoading: false,
    form: new FormData(),
    files: '',
    verificationScreenshot: [],

    emailScreenshot: [
      {
        name: 'FileRepeater',
        value: {},
      },
    ],
    chatScreenshot: [
      {
        name: 'FileRepeater',
        value: {},
      },
    ],
    openedItem: '',
    internetShopForm: false,
    internetShopSearch: '',
    headers: [
      {
        text: 'Company',
        align: 'left',
        sortable: true,
        value: 'dealer.name',
      },
      {
        text: 'Specific Dealer',
        sortable: true,
        value: 'specific_dealer.name',
      },
      {
        text: 'Secret Shopper',
        value: 'posted_by.name',
        sortable: true,
      },
      {
        text: 'Date Created',
        value: 'created_at',
        sortable: true,
      },
      // {
      //   text: 'Shop Type',
      //   value: 'type',
      //   sortable: true,
      // },
      {
        text: 'Category',
        value: 'category',
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
    editedIndex: -1,
    editedItem: {
      dealer: {
        is_dealer: true,
        id: '',
        name: '',
        timezone: '',
        competitor: '',
        zipcode: '',
      },
      location: {
        state: '',
        city: '',
      },
      shop: {
        is_new: '',
        sourceLink: '',
        salesPersonName: '',
        csmName: '',
        callGuideLink: '',
        startAt: '',
        duration: '',
        secretShopperId: null,
      },
      lead: {
        source: 0,
        name: '',
        email: '',
        phoneNumber: '',
      },
      car: {
        vin: '',
        make: '',
        model: '',
      },
      call: {
        first: {
          received: '',
          response: '',
        },
        attempts: 0,
      },
      sms: {
        first: {
          received: '',
          response: '',
        },
        attempts: 0,
      },
      email: {
        first: {
          received: '',
          response: '',
        },
        second: {
          received: '',
          response: '',
        },
        attempts: 0,
      },
      chat: {
        first: {
          received: '',
          response: '',
        },
        attempts: 0,
      },
    },
    defaultItem: resetToDefaultValues(),
    citiesList: [],
    hourMask: '##:##:##',
    phoneNumbersSearchResult: [],
    aws_url: '',
  }),
  created() {
    this.toggleAllFilter();
    this.fetchData();
    this.$store.dispatch('leadsources/getLeadSource');
    this.$http.get('venv/aws-url').then((response) => {
      this.aws_url = response.data.aws_url;
    });
  },
  mounted() {
    this.$validator.localize('en', {
      attributes: {
        'dealer.is_dealer': 'dealer type',
        'dealer.id': 'dealer group',
        'dealer.name': 'specific dealer',
        'dealer.zipcode': 'Zip Code',
        'dealer.timezone': 'dealer timezone',
        'location.state': 'state',
        'location.city': 'city',
        'shop.is_new': 'shop type',
        'lead.source': 'lead source',
        'shop.sourceLink': 'shop source link',
        'car.vin': 'vehicle identification number',
        'car.make': 'make of the car',
        'car.model': 'model of the car',
        'lead.name': 'lead name',
        'lead.email': 'lead email',
        'lead.phoneNumber': 'lead phone number',
        'shop.salesPersonName': "sales person's name",
        'shop.csmName': 'csm name',
        'shop.startAt': 'shop start date and time',
        'shop.duration': 'shop duration',
        verificationScreenshot: 'verification screenshot',
      },
    });
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1
        ? 'New Internet Shop'
        : 'Edit Internet Shop';
    },
    states() {
      return this.$store.state.states.states;
    },
    leadsources() {
      return this.$store.state.leadsources.leadsources;
    },
    cities() {
      return this.$store.state.cities.cities;
    },
    dealers() {
      return this.$store.state.dealers.dealers;
    },
    secretShopperList() {
      return this.$store.state.secretShoppers.secretShoppers;
    },
    shops() {
      return this.$store.state.internetShops.internetShops.data;
    },
    numberList() {
      return _map(
        this.$store.state.phoneNumbers.phoneNumbers.data,
        (number) => number.value
      );
    },
    specificDealersResult() {
      if (this.editedIndex == -1) {
        this.clearSelectedSpecificDealer();
        this.clearSelectedCompetitor();
      }

      if (!this.editedItem.dealer.id) {
        return [];
      }

      const dealerId = this.editedItem.dealer.id;
      const dealers = _filter(this.dealers, ['id', dealerId]);

      if (_head(dealers) == undefined) {
        return [];
      }

      const dealer = _head(dealers);
      const specificDealers = dealer.specific_dealers;

      return specificDealers;
    },
    competitorsResult() {
      if (
        !this.editedItem.dealer.name ||
        isNaN(parseInt(this.editedItem.dealer.name))
      ) {
        return [];
      }

      const specificDealerId = this.editedItem.dealer.name;
      const specificDealers = _filter(this.specificDealersResult, [
        'id',
        specificDealerId,
      ]);

      if (_head(specificDealers) == undefined) {
        return [];
      }

      const specificDealer = _head(specificDealers);
      const competitors = specificDealer.competitors;

      return competitors;
    },
    isCompetitor: {
      get() {
        return !this.editedItem.dealer.is_dealer;
      },
      set() {
        this.editedItem.dealer.is_dealer = !this.editedItem.dealer.is_dealer;
      },
    },
  },
  watch: {
    internetShopForm(val) {
      // eslint-disable-next-line no-unused-expressions
      val || this.close();
    },
    options: {
      handler(opts) {
        this.pagination.current_page = opts.page;
        this.fetchData();
      },
      deep: true,
    },
  },
  methods: {
    openShop(item) {
      let route = this.$router.resolve({
        path: `/client/internet-shop/${item.id}`,
      });
      window.open(route.href, '_blank');
    },
    editShop(item) {
      let route = this.$router.resolve({
        path: `/secretshop-management/internet-shop/${item.id}`,
      });
      window.open(route.href, '_blank');
    },
    clearSelectedSpecificDealer() {
      this.editedItem.dealer.name = '';
    },
    clearSelectedCompetitor() {
      this.editedItem.dealer.competitor = '';
    },
    menuProps() {},
    blurItem(event) {
      document.getElementsByClassName('v-list')[0].style.display = 'none';
    },
    focusItem() {
      document.getElementsByClassName('v-list')[0].style.display = 'block';
    },
    toggleAllFilter() {
      this.filters.status = ['Active', 'Inactive'];
    },
    emailOrderChanged(evt) {
      this.emailThumb.forEach(function (item, index) {
        item.order = index;
      });
    },
    emailScreenshotBlur(evt) {
      //console.info(evt.target.files);
      var i = 0;
      // this.emailThumb = [];
      // console.log(evt.target.files.length);
      console.log(evt);
      Array.from(evt.target.files).forEach((file) => {
        var input = file;
        // Ensure that you have a file before attempting to read it

        // create a new FileReader to read this image and convert to base64 format
        var reader = new FileReader();

        var vm = this;
        // Define a callback function to run, when FileReader finishes its job
        reader.onload = (e) => {
          // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
          // Read image as base64 and set to imageData
          let tempData = {};
          tempData.img64 = e.target.result;
          tempData.name = file.name;
          tempData.order = vm.emailThumb.length;
          this.emailThumb.push(tempData);
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input);
        i++;
      });
      this.preview.email_screenshots = null;
    },
    secondsToHMS(duration) {
      if (!duration) return '';
      var seconds = duration / 1000;
      var minutes = seconds / 60;
      var hours = minutes / 60;
      var days = hours / 24;

      if (days > 1) hours = days * 24 + (hours % 24);
      else hours = hours % 24;
      return (
        Math.floor(hours) +
        ':' +
        Math.floor(minutes % 60) +
        ':' +
        Math.floor(seconds % 60)
      );
    },
    dataURLtoFile(dataurl, filename) {
      var arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);

      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }

      return new File([u8arr], filename, { type: mime });
    },
    calculateCallResponseTime(inputDate) {
      // this.editedItem.call.first.response = new Date(
      //   this.editedItem.call.first.received - this.editedItem.shop.startAt
      // );

      // let callFirstReceived = new Date(
      //   this.editedItem.call.first.received
      // ).getTime();

      // let smsFirstReceived = new Date(
      //   this.editedItem.sms.first.received
      // ).getTime();

      // let emailFirstReceived = new Date(
      //   this.editedItem.email.first.received
      // ).getTime();

      // let chatFirstReceived = new Date(
      //   this.editedItem.chat.first.received
      // ).getTime();

      // let startDate = new Date(this.editedItem.shop.startAt).getTime();

      // replace the old cold with this
      // this is for call only, add for sms and email and chat

      // add conditional to avoid ugly format or result
      // const start = moment(this.editedItem.shop.startAt);
      // const end = moment(call.firstReceived.date);
      // console.log('start', start);
      // console.log('end', end);
      // console.log(this.secondsToHMS(end.diff(start)));

      if (this.editedItem.shop.startAt && this.editedItem.call.first.received) {
        const start = moment(this.editedItem.shop.startAt);
        const end = moment(this.editedItem.call.first.received);
        this.editedItem.call.first.response = this.secondsToHMS(
          end.diff(start)
        );
      } else {
        this.editedItem.call.first.response = '';
      }

      if (this.editedItem.shop.startAt && this.editedItem.sms.first.received) {
        const start = moment(this.editedItem.shop.startAt);
        const end = moment(this.editedItem.sms.first.received);
        this.editedItem.sms.first.response = this.secondsToHMS(end.diff(start));
      } else {
        this.editedItem.sms.first.response = '';
      }

      if (
        this.editedItem.shop.startAt &&
        this.editedItem.email.first.received
      ) {
        const start = moment(this.editedItem.shop.startAt);
        const end = moment(this.editedItem.email.first.received);
        this.editedItem.email.first.response = this.secondsToHMS(
          end.diff(start)
        );
      } else {
        this.editedItem.email.first.response = '';
      }

      if (this.editedItem.shop.startAt && this.editedItem.chat.first.received) {
        const start = moment(this.editedItem.shop.startAt);
        const end = moment(this.editedItem.chat.first.received);
        this.editedItem.chat.first.response = this.secondsToHMS(
          end.diff(start)
        );
      } else {
        this.editedItem.chat.first.response = '';
      }

      // this.editedItem.call.first.response = this.secondsToHMS(
      //   callFirstReceived - startDate
      // );

      // this.editedItem.sms.first.response = this.secondsToHMS(
      //   smsFirstReceived - startDate
      // );

      // this.editedItem.email.first.response = this.secondsToHMS(
      //   emailFirstReceived - startDate
      // );

      // this.editedItem.chat.first.response = this.secondsToHMS(
      //   chatFirstReceived - startDate
      // );
    },
    removeImage(index, screenshots, thumb) {
      thumb.splice(index, 1);
      screenshots.splice(index, 1);
    },
    fetchData() {
      // find a way para ma update current page sa pagination naten

      this.pagination.sortBy =
        this.options.sortBy !== undefined ? this.options.sortBy[0] : '';
      this.pagination.sortDesc =
        this.options.sortDesc !== undefined ? this.options.sortDesc[0] : '';

      this.isFetchingRecords = true;
      this.isFetchingData = true;

      this.$store
        .dispatch('internetShops/getInternetShops', {
          pagination: this.pagination,
          filters: this.filters,
        })
        .then(({ data }) => {
          this.$store.commit('internetShops/setInternetShops', data);

          this.pagination.total = data.total;
          // this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => {
          this.isFetchingRecords = false;
          this.isFetchingData = false;
        });
    },
    changePage(page) {
      this.pagination.current_page = page;
      this.triggerMethod = 'changepage';
      // this.fetchData();
    },
    fetchTwilioData() {
      this.editedItem.sms.attempts = '';
      this.editedItem.sms.first.received = '';
      this.editedItem.sms.first.response = '';

      this.editedItem.call.attempts = '';
      this.editedItem.call.first.received = '';
      this.editedItem.call.first.response = '';

      this.editedItem.email.attempts = '';
      this.editedItem.email.first.received = '';
      this.editedItem.email.first.response = '';

      this.editedItem.chat.attempts = '';
      this.editedItem.chat.first.received = '';
      this.editedItem.chat.first.response = '';

      this.$notify('success', 'Fetching data from twilio...');
      this.twilioLoading = true;
      this.$http
        .post('/twilio/get-specific-logs-by-number', {
          number: this.editedItem.lead.phoneNumber,
          call: true,
          sms: true,
          timezone: this.editedItem.dealer.timezone
            ? this.editedItem.dealer.timezone
            : 'EST',
        })
        .then(({ data }) => {
          if (data.sms) {
            const { sms } = data;
            this.editedItem.sms.attempts = sms.attempts;
            this.editedItem.sms.first.received = new Date(sms.firstReceived);
          }
          if (data.call) {
            const { call } = data;
            this.editedItem.call.attempts = call.attempts;
            this.editedItem.call.first.received = new Date(call.firstReceived);
          }
        })
        .catch((error) => {
          this.twilioLoading = false;
          this.$notify(
            'danger',
            'Something went wrong in fetching the Twilio data.'
          );
        })
        .finally(() => {
          this.twilioLoading = false;
          this.$notify(
            'success',
            'Please check the fields. If there are no value(s) it means that the the data is not existing at the moment.'
          );
        });
    },
    fetchMakeAndModel() {
      this.editedItem.car.make = '';
      this.editedItem.car.model = '';

      if (this.editedIndex === -1) {
        this.$validator
          .validate('car.vin', this.editedItem.car.vin, 'car.vin')
          .then((result) => {
            if (result) {
              this.vinLoading = true;
              this.hitVinApi();
            }
          });
      }
    },
    hitVinApi() {
      this.vinLoading = true;

      this.$http
        .post('/vin', { vin: this.editedItem.car.vin })
        .then(({ data }) => {
          this.editedItem.car.make = data.make;
          this.editedItem.car.model = data.model;
          this.$notify(
            'success',
            'The make and model input field has been automatically updated. Please double check it...'
          );
        })
        .catch(() => {
          this.$notify(
            'error',
            `Cant't find a make and model for this vin ${this.editedItem.car.vin}`
          );
        })
        .finally(() => {
          this.vinLoading = false;
        });
    },
    fetchTwilioSmsData() {
      this.$http
        .get(
          `/secret-shop-management/internet-shops/fetch-sms/${this.editedIndex}`
        )
        .then((response) => {
          alert('Fetched! Please reload');
        });
    },
    setCities(id) {
      this.$http.get(`/cities/${id}`).then(({ data }) => {
        this.$store.dispatch('cities/setCities', data);
      });
    },
    editItem(item) {
      let vm = this;

      this.openedItem = this.shops.indexOf(item);
      this.editedIndex = item.id;
      if (item.state) {
        this.setCities(item.state.id);
      }
      this.editedItem.dealer.id = item.dealer_id;
      this.editedItem.dealer.zipcode = item.zipcode;
      this.editedItem.dealer.timezone = item.timezone;
      this.editedItem.dealer.name = item.specific_dealer.name;

      const competitor = _find(item.specific_dealer.competitors, {
        id: item.competitor_id,
      });
      this.editedItem.dealer.competitor =
        competitor != undefined && competitor.name != undefined
          ? competitor.name
          : '';
      this.editedItem.dealer.timezone = item.timezone;
      this.editedItem.dealer.is_dealer = item.is_dealer === 1;

      if (item.state) {
        this.editedItem.location.state = item.state.id;
      }
      if (item.city) {
        this.editedItem.location.city = item.city.id;
      }
      this.editedItem.shop.is_new = String(item.is_shop_new);
      this.editedItem.shop.csmName = item.csm_name;
      this.editedItem.shop.sourceLink = item.source_link;
      this.editedItem.shop.salesPersonName = item.salesperson_name;
      this.editedItem.shop.secretShopperId = item.user_id;
      this.editedItem.shop.callGuideLink = item.call_guide_link;
      this.editedItem.shop.duration = item.shop_duration;
      this.editedItem.shop.startAt =
        '' != item.start_at ? this.formatDate(item.start_at) : '';

      this.editedItem.car.vin = item.vehicle_identification_number;
      this.editedItem.car.make = item.make;
      this.editedItem.car.model = item.model;
      this.editedItem.verification_screenshot = item.verification_screenshot;

      this.editedItem.lead.name = item.lead_name;
      this.editedItem.lead.source =
        null !== item.lead_source ? parseInt(item.lead_source) : null;

      this.editedItem.lead.phoneNumber = item.lead_phone_number;
      this.editedItem.lead.email = item.lead_email;
      if (item.call_first_received) {
        this.editedItem.call.first.received = this.formatDate(
          item.call_first_received
        );
      } else {
        if (vm.$refs.firstCallReceived !== undefined) {
          vm.$refs.firstCallReceived.clearHandler();
        }
      }

      this.editedItem.call.first.response = item.call_response_time;
      this.editedItem.call.attempts = item.call_attempts;

      if (item.sms_first_received) {
        this.editedItem.sms.first.received = this.formatDate(
          item.sms_first_received
        );
      } else {
        if (vm.$refs.firstSmsReceived !== undefined) {
          vm.$refs.firstSmsReceived.clearHandler();
        }
      }
      this.editedItem.sms.first.response = item.sms_response_time;
      this.editedItem.sms.attempts = item.sms_attempts;
      if (item.email_first_received) {
        this.editedItem.email.first.received = this.formatDate(
          item.email_first_received
        );
      } else {
        if (vm.$refs.firstEmailReceived !== undefined) {
          vm.$refs.firstEmailReceived.clearHandler();
        }
      }

      this.editedItem.email.first.response = item.email_response_time;
      this.editedItem.email.attempts = item.email_attempts;

      // if (item.email_second_received) {
      //   this.editedItem.email.second.received = this.formatDate(
      //     item.email_second_received
      //   );
      // } else {
      // if(vm.$refs.secondEmailReceived !== undefined) {
      //   vm.$refs.secondEmailReceived.clearHandler();
      // }
      // }

      // this.editedItem.email.second.response = item.email_second_response_time;
      // if (item.chat_first_received) {
      //   this.editedItem.chat.first.received = this.formatDate(
      //     item.chat_first_received
      //   );
      // } else {
      // if(vm.$refs.firstChatReceived !== undefined) {
      //   vm.$refs.firstChatReceived.clearHandler();
      // }
      // }

      this.editedItem.chat.first.response = item.chat_response_time;
      this.editedItem.chat.attempts = item.chat_attempts;

      // para sa edit item ma clear mga laman ng screenshots
      this.emailThumb = [];

      // eto ung para sa update nang masama existing uploaded images..
      // comment ka muna ngayon
      // this.temp.emailScreenshots = [];

      _forEach(item.email_screenshots, (screenshot) => {
        this.emailThumb.push({
          id: screenshot.id,
          webp: `https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/${item.id}/email/${screenshot.value}`,
          jpg: `https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/${item.id}/email/${screenshot.fall_back}`,
          order: screenshot.order_by,
          old: true,
        });
      });

      this.internetShopForm = true;
    },
    formatDate(date) {
      if (date) {
        return new Date(date);
      }
      return null;
    },
    deleteItem(item) {
      const index = this.shops.indexOf(item);
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
            .dispatch('internetShops/deleteInternetShop', item.id)
            .then(() => {
              this.shops.splice(index, 1);
              this.$notify('success', 'Internet Shop recored removed!');
            });
        }
      });
    },
    restoreItem(item) {
      const { id } = item;
      this.$store
        .dispatch('internetShops/restoreInternetShop', id)
        .then(({ data }) => {
          this.$notify('success', 'Internet Shop item successfully restored!');
          this.fetchData();
        });
    },
    checkIfNull(fields) {
      return _size(_filter(fields, { active: true })) >= 1;
    },
    mapFields(fields, name) {
      const files = _map(fields);

      _forEach(files, (val) => {
        this.form.append(name, val);
      });
    },
    setFormData() {
      const { shop, call, email, sms } = JSON.parse(
        JSON.stringify(this.editedItem)
      );
      const dateFormat = 'MM/DD/YYYY hh:mm a';

      shop.startAt = dayjs(shop.startAt).format(dateFormat);

      call.first.received = call.first.received
        ? dayjs(call.first.received).format(dateFormat)
        : null;
      sms.first.received = sms.first.received
        ? dayjs(sms.first.received).format(dateFormat)
        : null;
      email.first.received = email.first.received
        ? dayjs(email.first.received).format(dateFormat)
        : null;
      email.second.received = email.second.received
        ? dayjs(email.second.received).format(dateFormat)
        : null;

      this.form = new FormData();
      this.form.append('dealer', JSON.stringify(this.editedItem.dealer));
      this.form.append('location', JSON.stringify(this.editedItem.location));
      this.form.append('shop', JSON.stringify(shop));
      this.form.append('lead', JSON.stringify(this.editedItem.lead));
      this.form.append('car', JSON.stringify(this.editedItem.car));
      this.form.append('call', JSON.stringify(call));
      this.form.append('sms', JSON.stringify(sms));
      this.form.append('email', JSON.stringify(email));
      this.form.append('chat', JSON.stringify(this.editedItem.chat));

      if (this.emailThumb.length != null) {
        _forEach(_map(this.emailThumb), (screenshot, index) => {
          const vm = this;
          this.form.append(
            'emailScreenshots[' + index + '][order]',
            screenshot.order
          );
          if (screenshot.id != undefined) {
            this.form.append(
              'emailScreenshots[' + index + '][id]',
              screenshot.id
            );
          } else {
            this.form.append(
              'emailScreenshots[' + index + '][file]',
              vm.dataURLtoFile(screenshot.img64, 'email ' + index)
            );
          }
        });
      }

      if (this.verificationScreenshot != null) {
        this.form.append('verificationScreenshot', this.verificationScreenshot);
      }
    },
    store() {
      this.setFormData();
      this.axios
        .post('/secret-shop-management/internet-shops', this.form, {
          headers: {
            'Content-type': 'multipart/form-data',
          },
        })
        // eslint-disable-next-line no-unused-vars
        .then((response) => {
          this.$store.commit(
            'internetShops/insertNewInternetShop',
            response.data
          );
          this.$notify('success', 'Internet shop added!');
          this.close();
        })
        .catch((errors) => {
          console.log(errors);
          const messages = errors.response.data.errors;
          _forEach(messages, (val, key) => {
            this.errors.add({
              field: key,
              msg: val[0],
            });
          });
          this.scrollToFirstError();
        })
        .finally(() => {
          this.processingData = false;
        });
    },
    update() {
      this.setFormData();

      this.form.append('_method', 'PATCH');
      this.form.append('id', this.editedIndex);
      this.axios
        .post(
          `/secret-shop-management/internet-shops/${this.editedIndex}`,
          this.form,
          {
            headers: {
              'Content-type': 'multipart/form-data',
            },
          }
        )
        .then(({ data }) => {
          this.$notify('success', 'Internet shop record updated');
          const index = this.openedItem;
          this.$store.commit('internetShops/editInternetShop', {
            data,
            index,
          });
          // this.shops.splice(index, 1, data);
          this.close();
        })
        .catch((errors) => {
          console.log(errors);
          const messages = errors.response.data.errors;
          _forEach(messages, (val, key) => {
            this.errors.add({
              field: key,
              msg: val[0],
            });
          });
          this.scrollToFirstError();
        })
        .finally(() => {
          this.processingData = false;
        });
    },
    save() {
      this.$validator.validate('*').then((result) => {
        if (result) {
          this.processingData = true;
          if (this.editedIndex > -1) {
            this.update();
          } else {
            this.store();
          }
        } else {
          this.scrollToFirstError();
        }
      });
    },
    scrollToFirstError() {
      if (null !== document.querySelector('.validate-error:first-of-type')) {
        const el = document.querySelector('.validate-error:first-of-type')
          .parentNode;
        el.scrollIntoView();
      }
    },
    close() {
      const vm = this;
      vm.$refs.startDateAndTime.clearHandler();
      vm.$refs.firstCallReceived.clearHandler();
      vm.$refs.firstSmsReceived.clearHandler();
      vm.$refs.firstEmailReceived.clearHandler();
      // vm.$refs.secondEmailReceived.clearHandler();
      // vm.$refs.firstChatReceived.clearHandler();
      this.internetShopForm = false;
      this.$nextTick(() => {
        this.$validator.reset();
        this.editedIndex = -1;
        this.editedItem = Object.assign({}, resetToDefaultValues());
        this.verificationScreenshot = [];
        this.chatThumb = [];
        this.emailThumb = [];
        this.temp.emailScreenshots = [];
        this.temp.chat_screenshots = [];
      });
    },
    pluckDealerId(selectedOption, id) {
      this.editedItem.dealer.id = selectedOption.id;
    },
    searchPhoneNumber(search, loading) {
      this.$http
        .get('/secret-shop-management/phone-numbers/search', {
          params: {
            search,
          },
        })
        .then((response) => {
          console.log(response);
          this.phoneNumbersSearchResult = response.data.numbers;
        });
    },
    checkSpecificDealerDuplication(val) {
      if (isNaN(val)) {
        const partialMatches = _filter(this.specificDealersResult, (dealer) => {
          return dealer.name.includes(val);
        });
        if (partialMatches.length > 0) {
          this.$swal({
            title: 'Are you sure?',
            text:
              'It seems there are partial matches on your search, please double check before creating to avoid duplication',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, continue!',
            cancelButtonText: 'No',
            showCloseButton: true,
          }).then((result) => {
            if (!result.value) {
              this.editedItem.dealer.name = '';
            }
          });
        }
      }
    },
  },
};
</script>
<style lang="stylus" scoped>
adjustImage {
  width: 400px;
  height: 400px;
}
</style>
