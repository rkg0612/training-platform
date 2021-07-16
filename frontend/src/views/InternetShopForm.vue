<template>
  <div>
    <v-card>
      <v-tabs
        next-icon="fad fa-arrow-square-right"
        prev-icon="fad fa-arrow-square-left"
        grow
        v-model="tab"
        icons-and-text
      >
        <v-tab>
          Basic Information
          <v-icon>fal fa-info-circle</v-icon>
        </v-tab>
        <v-tab>
          Response Time and Attempts
          <v-icon>fal fa-clock</v-icon>
        </v-tab>
        <v-tab v-if="isTrueCar">
          For TrueCar
          <v-icon>fal fa-car</v-icon>
        </v-tab>
        <!-- <v-tab> Chat </v-tab> -->
        <v-tab-item class="p-5">
          <h5>Dealer's Info</h5>
          <v-row>
            <v-col cols="12" sm="12" md="4">
              <v-switch
                v-model="isCompetitor"
                name="is_dealer"
                label="is Competitor"
                color="#f9b418"
              />
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('is_dealer')"
                >{{ errors.first('is_dealer') }}</span
              >
              <!--              <v-radio-group
                name="is_dealer"
                v-validate="'required'"
                v-model="shop.is_dealer"
                row
                ref="is_dealer"
              >
                <v-radio
                  label="Competitor"
                  value="0"
                  color="yellow darken-3"
                  key="0"
                ></v-radio>
                <v-radio
                  label="Dealer"
                  value="1"
                  color="yellow darken-3"
                  key="1"
                ></v-radio>
              </v-radio-group>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('is_dealer')"
                >{{ errors.first('is_dealer') }}</span>-->
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <vue-select
                v-model="shop.dealer_id"
                name="dealer_id"
                :options="dealers"
                :reduce="(dealer) => dealer.id"
                v-validate="'required'"
                :loading="processingData"
                :disabled="processingData"
                label="name"
              >
              </vue-select>
              <span
                class="kt-font-primary"
                v-if="!shop.dealer_id && !errors.first('dealer_id')"
              >
                Choose a company
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('dealer_id')"
                >{{ errors.first('dealer_id') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <vue-select
                v-model="shop.specific_dealer_id"
                name="specific_dealer_id"
                autocomplete="new-password"
                v-validate="'required'"
                taggable
                :options="specificDealersResult"
                :reduce="(dealer) => dealer.id"
                :create-option="(dealer) => ({ name: dealer, id: dealer })"
                label="name"
                :loading="processingData"
                :disabled="processingData"
                @input="checkSpecificDealerDuplication"
              />
              <span
                class="kt-font-primary"
                v-if="
                  !shop.specific_dealer_id &&
                  !errors.first('specific_dealer_id')
                "
              >
                Choose a specific dealer
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('specific_dealer_id')"
                >{{ errors.first('specific_dealer_id') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <vue-select
                v-model="shop.competitor_id"
                name="competitor_id"
                autocomplete="new-password"
                v-validate="{
                  required: isCompetitor,
                }"
                taggable
                :options="competitorsResult"
                :loading="processingData"
                :reduce="(competitor) => competitor.id"
                :create-option="
                  (competitor) => ({ name: competitor, id: competitor })
                "
                label="name"
                :disabled="!isCompetitor"
              />
              <span
                class="kt-font-primary"
                v-if="!shop.competitor_id && !errors.first('competitor_id')"
              >
                Choose a competitor
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('competitor_id')"
                >{{ errors.first('competitor_id') }}
              </span>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <vue-select
                v-model="shop.timezone"
                :options="['EST', 'CST', 'MST', 'PST', 'AST', 'HST']"
                :menu-props="{ closeOnClick: true }"
                label="Timezone"
                v-validate="'required'"
                name="timezone"
                autocomplete="new-password"
              />
              <span
                class="kt-font-primary"
                v-if="!shop.timezone && !errors.first('timezone')"
              >
                Timezone
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('timezone')"
                >{{ errors.first('timezone') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-validate="'required'"
                name="zipcode"
                v-model="shop.zipcode"
                label="Zip Code"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('zipcode')"
                >{{ errors.first('zipcode') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <vue-select
                v-model="shop.state_id"
                :options="states"
                label="name"
                :reduce="(state) => state.id"
                @input="fetchCities"
                autocomplete="new-password"
                v-validate="'required'"
                name="state_id"
                :loading="processingData"
                :disabled="processingData"
              >
              </vue-select>
              <span
                class="kt-font-primary"
                v-if="!shop.state_id && !errors.first('state_id')"
              >
                Choose a state
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('state_id')"
                >{{ errors.first('state_id') }}</span
              >
            </v-col>

            <v-col cols="12" sm="12" md="4">
              <vue-select
                v-model="shop.city_id"
                :options="cities"
                label="value"
                :reduce="(city) => city.id"
                autocomplete="new-password"
                v-validate="'required'"
                name="city_id"
                :loading="processingData"
                :disabled="processingData"
              />
              <span
                class="kt-font-primary"
                v-if="!shop.city_id && !errors.first('city_id')"
              >
                Choose a city
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('city_id')"
                >{{ errors.first('city_id') }}</span
              >
            </v-col>
          </v-row>
          <h5>Shop's Info</h5>
          <v-row>
            <v-col cols="12" sm="12" md="4">
              <v-radio-group
                v-model="shop.is_shop_new"
                v-validate="'required'"
                name="is_shop_new"
                row
              >
                <v-radio
                  label="New"
                  value="1"
                  color="yellow darken-3"
                ></v-radio>
                <v-radio
                  label="Used"
                  value="0"
                  color="yellow darken-3"
                ></v-radio>
              </v-radio-group>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('is_shop_new')"
                >{{ errors.first('is_shop_new') }}</span
              >
            </v-col>

            <v-col cols="12" sm="12" md="4">
              <vue-select
                v-model="shop.lead_source"
                :options="leadsources"
                name="lead_source"
                label="value"
                :reduce="(source) => source.id"
                autocomplete="new-password"
                v-validate="'required'"
                @blur="blurItem()"
                @input="focusItem()"
                taggable
                push-tags
                :loading="processingData"
                :disabled="processingData"
              />
              <span
                class="kt-font-primary"
                v-if="!shop.lead_source && !errors.first('lead_source')"
              >
                Select a lead source
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('lead_source')"
                >{{ errors.first('lead_source') }}</span
              >
            </v-col>

            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-validate="'required'"
                name="source_link"
                v-model="shop.source_link"
                label="Source's Link"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('source_link')"
                >{{ errors.first('source_link') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-validate="'required'"
                name="vehicle_identification_number"
                @blur="fetchMakeAndModel"
                v-model="shop.vehicle_identification_number"
                label="Enter VIN"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('vehicle_identification_number')"
                >{{ errors.first('vehicle_identification_number') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.make"
                :loading="vinLoading"
                v-validate="'required'"
                name="make"
                label="Make of the Car"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('make')"
                >{{ errors.first('make') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.model"
                :loading="vinLoading"
                v-validate="'required'"
                name="model"
                label="Model of the Car"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('model')"
                >{{ errors.first('model') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.lead_name"
                v-validate="'required'"
                name="lead_name"
                label="Lead's Name"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('lead_name')"
                >{{ errors.first('lead_name') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-validate="'required'"
                name="lead_email"
                v-model="shop.lead_email"
                label="Lead's Email"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('lead_email')"
                >{{ errors.first('lead_email') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-validate="'required'"
                name="salesperson_name"
                v-model="shop.salesperson_name"
                label="Salesperson's Name"
                autocomplete="new-password"
              />
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('salesperson_name')"
                >{{ errors.first('salesperson_name') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-select
                v-model="shop.user_id"
                name="user_id"
                v-validate="'required'"
                :items="secretShoppers"
                label="Secret Shopper"
                item-text="name"
                item-value="id"
                :loading="processingData"
                :disabled="processingData"
              ></v-select>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('user_id')"
                >{{ errors.first('user_id') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.call_guide_link"
                messages="Optional"
                label="Call Guide Link"
                autocomplete="new-password"
              />
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.shop_duration"
                v-validate="'required'"
                name="shop_duration"
                label="Shop Duration"
                autocomplete="new-password"
              ></v-text-field>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('shop_duration')"
                >{{ errors.first('shop_duration') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-file-input
                accept="image/png, image/jpeg, image/bmp"
                label="Verification Screenshot"
                v-validate="{
                  ext: ['png', 'jpg'],
                  required: !this.isEdit,
                }"
                ref="verificationScreenshot"
                v-model="verificationScreenshot"
                name="verificationScreenshot"
              />
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('verificationScreenshot')"
                >{{ errors.first('verificationScreenshot') }}</span
              >
            </v-col>
            <v-col v-if="shop.verification_screenshot" cols="12" sm="12" md="4">
              <h6 class="text-center">Current Verification Screenshot</h6>
              <p class="text-center font-weight-medium">
                Click the image to see full size.
              </p>
              <a
                :href="`${aws_url}verification-screenshots/${shop.verification_screenshot}`"
                target="_blank"
                class="text-center"
              >
                <v-img
                  aspect-ratio="2"
                  :src="`${aws_url}verification-screenshots/${shop.verification_screenshot}`"
                ></v-img>
              </a>
            </v-col>
          </v-row>
        </v-tab-item>
        <v-tab-item class="p-5">
          <v-row>
            <v-col cols="12" sm="12" md="6">
              <vue-select
                v-model="shop.lead_phone_number"
                @search="searchPhoneNumber"
                :options="phoneNumbersSearchResult"
                :loading="processingData"
                :disabled="processingData"
                autocomplete="new-password"
                v-validate="'required'"
                name="lead_phone_number"
                label="value"
                :reduce="(number) => number.value"
                @input="fetchTwilioData"
              />
              <span
                class="kt-font-primary"
                v-if="
                  !shop.lead_phone_number && !errors.first('lead_phone_number')
                "
              >
                Lead's Phone Number
              </span>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('lead_phone_number')"
                >{{ errors.first('lead_phone_number') }}</span
              >
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-datetime-picker
                v-validate="'required'"
                name="start_at"
                label="Start Date and Time"
                v-model="shop.start_at"
                @input="calculateCallResponseTime"
                okText="SET"
                dateIcon="fal fa-calendar-alt"
                timeIcon="fal fa-clock"
                date-format="MM-dd-yyyy"
                time-format="hh:mm a"
                ref="startDateAndTime"
              >
                <template slot="dateIcon">
                  <i class="fal fa-calendar-alt"></i>
                </template>
                <template slot="timeIcon">
                  <i class="fal fa-clock"></i>
                </template>
              </v-datetime-picker>
              <span
                class="kt-font-danger validate-error"
                v-if="errors.first('start_at')"
                >{{ errors.first('start_at') }}</span
              >
            </v-col>
          </v-row>
          <h5>Call Data</h5>
          <v-row>
            <v-col cols="12" sm="12" md="4">
              <v-datetime-picker
                label="First Call Received"
                v-model="shop.call_first_received"
                dateIcon="fal fa-calendar-alt"
                timeIcon="fal fa-clock"
                date-format="MM-dd-yyyy"
                time-format="hh:mm a"
                :loading="twilioLoading"
                ref="firstCallReceived"
              >
                <template slot="dateIcon">
                  <i class="fal fa-calendar-alt" />
                </template>
                <template slot="timeIcon">
                  <i class="fal fa-clock" />
                </template>
              </v-datetime-picker>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.call_response_time"
                label="Call Response Time"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.call_attempts"
                :loading="twilioLoading"
                label="Call Attempts"
              ></v-text-field>
            </v-col>
          </v-row>
          <h5>SMS Data</h5>
          <v-row>
            <v-col cols="12" sm="12" md="4">
              <v-datetime-picker
                label="First SMS Received"
                v-model="shop.sms_first_received"
                dateIcon="fal fa-calendar-alt"
                timeIcon="fal fa-clock"
                date-format="MM-dd-yyyy"
                time-format="hh:mm a"
                :loading="twilioLoading"
                ref="firstSmsReceived"
              >
                <template slot="dateIcon">
                  <i class="fal fa-calendar-alt" />
                </template>
                <template slot="timeIcon">
                  <i class="fal fa-clock" />
                </template>
              </v-datetime-picker>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.sms_response_time"
                label="SMS Response Time"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.sms_attempts"
                :loading="twilioLoading"
                label="SMS Attempts"
              ></v-text-field>
            </v-col>
          </v-row>
          <h5>Email Data</h5>
          <v-row>
            <v-col cols="12" sm="12" md="4">
              <v-datetime-picker
                label="First Email Received"
                v-model="shop.email_first_received"
                dateIcon="fal fa-calendar-alt"
                @input="calculateCallResponseTime"
                timeIcon="fal fa-clock"
                date-format="MM-dd-yyyy"
                time-format="hh:mm a"
                ref="firstEmailReceived"
              >
                <template slot="dateIcon">
                  <i class="fal fa-calendar-alt" />
                </template>
                <template slot="timeIcon">
                  <i class="fal fa-clock" />
                </template>
              </v-datetime-picker>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.email_response_time"
                label="Email Response Time"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.email_attempts"
                label="Email Attempts"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="6">
              <v-datetime-picker
                label="Second Email Received (For True Car)"
                v-model="shop.email_second_received"
                dateIcon="fal fa-calendar-alt"
                timeIcon="fal fa-clock"
                date-format="MM-dd-yyyy"
                time-format="hh:mm a"
                ref="secondEmailReceived"
              >
                <template slot="dateIcon">
                  <i class="fal fa-calendar-alt" />
                </template>
                <template slot="timeIcon">
                  <i class="fal fa-clock" />
                </template>
              </v-datetime-picker>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="shop.email_second_response_time"
                label="Second Email Response Time"
                messages="For True Car shops only"
              ></v-text-field>
            </v-col>
          </v-row>
          <h5>Chat Data</h5>
          <v-row>
            <v-col cols="12" sm="12" md="4">
              <v-datetime-picker
                label="First Chat Received"
                v-model="shop.chat_first_received"
                @input="calculateCallResponseTime"
                dateIcon="fal fa-calendar-alt"
                timeIcon="fal fa-clock"
                date-format="MM-dd-yyyy"
                time-format="hh:mm a"
                ref="firstChatReceived"
              >
                <template slot="dateIcon">
                  <i class="fal fa-calendar-alt" />
                </template>
                <template slot="timeIcon">
                  <i class="fal fa-clock" />
                </template>
              </v-datetime-picker>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.chat_first_response"
                label="Chat Response Time"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="4">
              <v-text-field
                v-model="shop.chat_attempts"
                label="Chat Attempts"
              ></v-text-field>
            </v-col>
          </v-row>
          <h5>Email Screenshots</h5>
          <v-row>
            <draggable
              v-model="temp.emailScreenshots"
              style="display: contents"
              @end="emailOrderChanged"
            >
              <v-col
                v-for="(screenshot, index) in temp.emailScreenshots"
                v-bind:key="index"
              >
                <v-card class="emailItems" dense width="400" shaped>
                  <v-card-actions>
                    <h4>{{ index + 1 }})</h4>

                    <v-spacer />
                    <v-badge
                      :content="index"
                      :value="index"
                      color="green"
                      overlap
                    >
                      <v-btn
                        icon
                        large
                        @click="
                          removeImage(
                            index,
                            preview.email_screenshots,
                            temp.emailScreenshots
                          )
                        "
                      >
                        <v-icon>fa-times-square</v-icon>
                      </v-btn>
                    </v-badge>
                  </v-card-actions>
                  <v-img>
                    <picture>
                      <img
                        :src="
                          'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/' +
                          editedIndex +
                          '/email/' +
                          screenshot.baseName
                        "
                        alt="Email Screenshot"
                        width="400"
                        height="225"
                      />
                    </picture>
                  </v-img>
                </v-card>
              </v-col>
            </draggable>
          </v-row>

          <v-row>
            <draggable
              v-model="emailThumb"
              style="display: contents"
              @end="emailOrderChanged"
            >
              <v-col
                v-for="(screenshot, index) in emailThumb"
                v-bind:key="index"
              >
                <v-card class="emailItems" dense width="400" shaped>
                  <v-card-actions>
                    <h4>{{ index + 1 }}). {{ screenshot.name }}</h4>

                    <v-spacer />
                    <v-badge
                      :content="index"
                      :value="index"
                      color="green"
                      overlap
                    >
                      <v-btn
                        icon
                        large
                        @click="
                          removeImage(
                            index,
                            preview.email_screenshots,
                            emailThumb
                          )
                        "
                      >
                        <v-icon>fa-times-square</v-icon>
                      </v-btn>
                    </v-badge>
                  </v-card-actions>
                  <v-img>
                    <picture>
                      <source
                        v-if="screenshot.old"
                        :srcset="screenshot.webp"
                        type="image/webp"
                      />
                      <source
                        v-if="screenshot.old"
                        :srcset="screenshot.jpg"
                        type="image/jpeg"
                      />
                      <img
                        v-if="screenshot.old"
                        :src="screenshot.jpg"
                        alt="Email Screenshot"
                      />
                      <img
                        v-if="!screenshot.old"
                        :src="screenshot.img64"
                        alt="Email Screenshot"
                        width="400"
                        height="225"
                      />
                    </picture>
                  </v-img>
                </v-card>
              </v-col>
            </draggable>
          </v-row>
          <!-- Need to Upload Multiple files with Preview -->
          <v-file-input
            v-model="preview.email_screenshots"
            placeholder="Upload your Email Screenshots"
            label="Email Screenshots(multiple allowed)"
            multiple
            prepend-icon="mdi-camera"
            accept="image/*"
            @blur="emailScreenshotBlur"
          >
            <template v-slot:selection="{ text }">
              <v-chip small label color="primary">{{ text }}</v-chip>
            </template>
          </v-file-input>
        </v-tab-item>
        <v-tab-item class="p-5">
          <true-car-fields
            :shop="trueCarFields"
            @update-tc-fields="updateTrueCarFields"
          />
        </v-tab-item>
      </v-tabs>
      <v-card-actions v-if="tab == tabCount">
        <v-spacer />
        <v-btn color="blue-grey darken-1" dark @click="prevTab" v-if="tab !== 0"
          >Previous</v-btn
        >
        <v-btn color="blue-grey darken-1" dark @click="closeShop">Cancel</v-btn>
        <v-btn
          color="blue darken-1"
          dark
          :loading="processingData"
          :disabled="processingData"
          @click="save"
          >Save</v-btn
        >
      </v-card-actions>
      <v-card-actions v-else>
        <v-spacer />
        <v-btn color="blue-grey darken-1" dark @click="prevTab" v-if="tab !== 0"
          >Previous</v-btn
        >
        <v-btn color="blue darken-1" dark @click="nextTab">Next</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
import {
  map as _map,
  filter as _filter,
  head as _head,
  find as _find,
  forEach as _forEach,
  size as _size,
  without as _without,
} from 'lodash-es';
import draggable from 'vuedraggable';
import moment from 'moment';
import dayjs from 'dayjs';
import TrueCarFields from '../components/secretshop/includes/TrueCarFields.vue';

function resetToDefaultValues() {
  return {
    shop: {
      is_dealer: true,
      dealer_id: '',
      specific_dealer_id: '',
      timezone: '',
      competitor_id: '',
      zipcode: '',
      state_id: '',
      city_id: '',
      is_shop_new: '',
      source_link: '',
      salesperson_name: '',
      call_guide_link: '',
      start_at: '',
      shop_duration: '',
      lead_source: '',
      lead_name: '',
      lead_email: '',
      lead_phone_number: '',
      vehicle_identification_number: '',
      make: '',
      model: '',
      user_id: '',
      verification_screenshot: '',
      call_first_received: '',
      call_response_time: '',
      call_attempts: 0,
      sms_first_received: '',
      sms_response_time: '',
      sms_attempts: 0,
      email_first_received: '',
      email_response_time: '',
      email_second_received: '',
      email_second_response_time: '',
      email_attempts: 0,
      chat_first_received: '',
      chat_response_time: '',
      chat_attempts: 0,
    },
  };
}

export default {
  name: 'Internet Shop Form',
  components: {
    draggable,
    TrueCarFields,
  },
  data: () => ({
    tab: null,
    loadingData: false,
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
    states: [],
    leadsources: [],
    cities: [],
    dealers: [],
    secretShoppers: [],
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
    editedIndex: -1,
    shop: {
      is_dealer: true,
      dealer_id: '',
      specific_dealer_id: '',
      timezone: '',
      competitor_id: '',
      zipcode: '',
      state_id: '',
      city_id: '',
      is_shop_new: '',
      source_link: '',
      salesperson_name: '',
      call_guide_link: '',
      start_at: '',
      shop_duration: '',
      lead_source: '',
      lead_name: '',
      lead_email: '',
      lead_phone_number: '',
      vehicle_identification_number: '',
      make: '',
      user_id: '',
      model: '',
      verification_screenshot: '',
      call_first_received: '',
      call_response_time: '',
      call_attempts: 0,
      sms_first_received: '',
      sms_response_time: '',
      sms_attempts: 0,
      email_first_received: '',
      email_response_time: '',
      email_second_received: '',
      email_second_response_time: '',
      email_attempts: 0,
      chat_first_received: '',
      chat_response_time: '',
      chat_attempts: 0,
    },
    trueCarFields: {
      //TRUECAR
      csm_name: '',
      field_leader_name: '',
      date_time_followup: '',
      recording: '',
      shop_type: '',
      recommendation_email: '',
      recommendation_call: '',
      recommendation_sms: '',
      general_note: '',
      //Basic Shop
      bs_honor_payments_tcdc: '',
      bs_send_picvid: '',
      bs_mention_ts_voicemail: '',
      bs_mention_ts_email: '',
      bs_mention_ts_text: '',
      bs_mention_ts_video: '',
      bs_attempt_appointment: '',
      bs_home_delivery_offer: '',
      //Text Only
      to_meet_char_limits: '',
      //Access BAse
      ab_provide_payment: '',
      ab_honor_offer_tcdc: '',
      ab_manual_discount_offer: '',
      ab_script_what_payment: '',
      ab_script_real_payment: '',
      //Military
      military_identify_rank: '',
      military_thank_customer: '',
      military_benefits_explained: '',
      military_script_benefits: '',
      //PenFed
      pf_honor_nf_policy: '',
      pf_contingencies: '',
      pf_contingencies_if_yes: '',
      pf_buyers_bonus: '',
      pf_manual_discount_offer: '',
      pf_script_approved_loan: '',
      //AMEX
      amex_allowed_amex: '',
      amex_mention_bonus: '',
      amex_manual_discount_offer: '',
      amex_script_use_amex: '',
      amex_script_limit: '',
      //Sams Club
      sc_unprompted_bonus: '',
      sc_bridge_rel_gap: '',
      sc_partners: '',
      sc_manual_discount_offer: '',
      sc_didnt_know_answer: '',
      sc_script_costco: '',
      sc_script_pandora: '',
      sc_script_what_payment: '',
      //Consumer Report
      cr_comments: '',
      cr_bridge_rel_gap: '',
      cr_appointment_attempt: '',
      cr_home_delivery: '',
      cr_partners: '',
      cr_manual_discount_offer: '',
      cr_didnt_know_answer: '',
      cr_script_my_products_web: '',
      cr_script_what_payment: '',
    },
    internetShop: {
      dealer: {
        is_dealer: '',
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
    //Check if edit page
    if (this.isEdit) {
      this.fetchInternetShop(this.$route.params.id);
    }
    this.fetchInit();
    this.$http.get('venv/aws-url').then((response) => {
      this.aws_url = response.data.aws_url;
    });
  },
  mounted() {
    this.$validator.localize('en', {
      attributes: {
        is_dealer: 'dealer type',
        dealer_id: 'dealer group',
        specific_dealer_id: 'specific dealer',
        zipcode: 'Zip Code',
        timezone: 'dealer timezone',
        state_id: 'state',
        city_id: 'city',
        is_shop_new: 'shop type',
        lead_source: 'lead source',
        source_link: 'shop source link',
        vehicle_identification_number: 'vehicle identification number',
        make: 'make of the car',
        model: 'model of the car',
        lead_name: 'lead name',
        lead_email: 'lead email',
        lead_phone_number: 'lead phone number',
        salesperson_name: "sales person's name",
        user_id: 'secret shopper',
        start_at: 'shop start date and time',
        shop_duration: 'shop duration',
        verificationScreenshot: 'verification screenshot',
      },
    });
  },
  computed: {
    isTrueCar() {
      return this.shop.dealer_id === 48;
    },
    tabCount() {
      if (this.isTrueCar) {
        return 2;
      }
      return 1;
    },
    isEdit() {
      return this.$route.params.id !== undefined;
    },
    numberList() {
      return _map(
        this.$store.state.phoneNumbers.phoneNumbers.data,
        (number) => number.value
      );
    },
    specificDealersResult() {
      if (!this.shop.dealer_id) {
        return [];
      }

      const dealerId = this.shop.dealer_id;
      const dealers = _filter(this.dealers, ['id', dealerId]);

      if (_head(dealers) == undefined) {
        return [];
      }

      const dealer = _head(dealers);
      const specificDealers = dealer.specific_dealers;

      return specificDealers;
    },
    competitorsResult() {
      if (!this.shop.specific_dealer_id) {
        return [];
      }

      const specificDealerId = this.shop.specific_dealer_id;
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
        return !this.shop.is_dealer;
      },
      set() {
        this.shop.is_dealer = !this.shop.is_dealer;
      },
    },
  },

  methods: {
    nextTab() {
      if (this.tab <= this.tabCount) {
        this.tab++;
      }
    },
    prevTab() {
      if (this.tab !== 0) {
        this.tab--;
      }
    },
    fetchInternetShop(id) {
      this.axios
        .get('/secret-shop-management/internet-shop/' + id)
        .then(({ data }) => {
          this.shop = data;
          this.fetchCities();
          this.shop.is_dealer = Boolean(data.is_dealer);
          this.shop.is_shop_new = String(data.is_shop_new);
          this.shop.lead_source = parseInt(data.lead_source);
          this.shop.start_at = data.start_at
            ? this.formatDate(data.start_at)
            : '';
          this.shop.call_first_received = data.call_first_received
            ? this.formatDate(data.call_first_received)
            : '';
          this.shop.sms_first_received = data.sms_first_received
            ? this.formatDate(data.sms_first_received)
            : '';
          this.shop.email_first_received = data.email_first_received
            ? this.formatDate(data.email_first_received)
            : '';
          this.shop.email_second_received = data.email_second_received
            ? this.formatDate(data.email_second_received)
            : '';
          this.shop.chat_first_received = data.chat_first_received
            ? this.formatDate(data.chat_first_received)
            : '';
          _forEach(data.email_screenshots, (screenshot) => {
            this.emailThumb.push({
              id: screenshot.id,
              webp: `https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/${data.id}/email/${screenshot.value}`,
              jpg: `https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/${data.id}/email/${screenshot.fall_back}`,
              order: screenshot.order_by,
              old: true,
            });
          });

          if (this.isTrueCar && data.truecar_fields) {
            this.trueCarFields = data.truecar_fields;
            this.trueCarFields.csm_name = data.csm_name;
            this.trueCarFields.date_time_followup = data.truecar_fields
              .date_time_followup
              ? this.formatDate(data.truecar_fields.date_time_followup)
              : '';
          }
        })
        .catch((error) => {
          this.$router.push({
            name: 'PageNotFound',
          });
        })
        .finally(() => {
          //
        });
    },
    updateTrueCarFields(fields) {
      this.trueCarFields = fields;
    },
    fetchInit() {
      this.processingData = true;
      this.fetchLeadSources();
      this.fetchStates();
      this.fetchDealers();
      this.fetchSecretShoppers();
      this.processingData = false;
    },
    fetchLeadSources() {
      this.axios.get('/leadsources').then(({ data }) => {
        this.leadsources = data;
      });
    },
    fetchStates() {
      this.axios.get('/states').then(({ data }) => {
        this.states = data;
      });
    },
    fetchCities() {
      this.processingData = true;
      this.axios
        .get('/cities/' + this.shop.state_id)
        .then(({ data }) => {
          this.cities = data;
        })
        .finally(() => {
          this.processingData = false;
        });
    },
    fetchDealers() {
      this.axios.get('/dealers').then(({ data }) => {
        this.dealers = data;
      });
    },
    fetchSecretShoppers() {
      this.axios.get('/users/secret-shoppers').then(({ data }) => {
        this.secretShoppers = data;
      });
    },
    openShop(item) {
      let route = this.$router.resolve({
        path: `/client/internet-shop/${item.id}`,
      });
      window.open(route.href, '_blank');
    },
    closeShop() {
      this.$swal({
        title: 'Are you sure?',
        text: 'You have unsaved data.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        showCloseButton: true,
      }).then((result) => {
        if (result.value) {
          window.close();
        }
      });
    },
    clearSelectedSpecificDealer() {
      this.shop.dealer_id = '';
    },
    clearSelectedCompetitor() {
      this.shop.competitor_id = '';
    },
    menuProps() {},
    blurItem(event) {
      document.getElementsByClassName('v-list')[0].style.display = 'none';
    },
    focusItem() {
      document.getElementsByClassName('v-list')[0].style.display = 'block';
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
      if (this.shop.start_at && this.shop.call_first_received) {
        const start = moment(this.shop.start_at);
        const end = moment(this.shop.call_first_received);
        this.shop.call_response_time = this.secondsToHMS(end.diff(start));
      } else {
        this.shop.call_response_time = '';
      }

      if (this.shop.start_at && this.shop.sms_first_received) {
        const start = moment(this.shop.start_at);
        const end = moment(this.shop.sms_first_received);
        this.shop.sms_response_time = this.secondsToHMS(end.diff(start));
      } else {
        this.shop.sms_response_time = '';
      }

      if (this.shop.start_at && this.shop.email_first_received) {
        const start = moment(this.shop.start_at);
        const end = moment(this.shop.email_first_received);
        this.shop.email_response_time = this.secondsToHMS(end.diff(start));
      } else {
        this.shop.email_response_time = '';
      }

      if (this.shop.start_at && this.shop.email_second_received) {
        const start = moment(this.shop.start_at);
        const end = moment(this.shop.email_second_received);
        this.shop.email_second_response_time = this.secondsToHMS(
          end.diff(start)
        );
      } else {
        this.shop.email_second_response_time = '';
      }

      if (this.shop.start_at && this.shop.chat_first_received) {
        const start = moment(this.shop.start_at);
        const end = moment(this.shop.chat_first_received);
        this.shop.chat_first_response = this.secondsToHMS(end.diff(start));
      } else {
        this.shop.chat_first_response = '';
      }
    },
    removeImage(index, screenshots, thumb) {
      thumb.splice(index, 1);
      screenshots.splice(index, 1);
    },
    fetchTwilioData() {
      this.shop.sms_attempts = '';
      this.shop.sms_first_received = '';
      this.shop.sms_response_time = '';

      this.shop.call_attempts = '';
      this.shop.call_first_received = '';
      this.shop.call_response_time = '';

      this.shop.email_attempts = '';
      this.shop.email_first_received = '';
      this.shop.email_response_time = '';

      this.shop.chat_attempts = '';
      this.shop.chat_first_received = '';
      this.shop.chat_first_response = '';

      this.$notify('success', 'Fetching data from twilio...');
      this.twilioLoading = true;
      this.$http
        .post('/twilio/get-specific-logs-by-number', {
          number: this.shop.lead_phone_number,
          call: true,
          sms: true,
          timezone: this.shop.timezone ? this.shop.timezone : 'EST',
        })
        .then(({ data }) => {
          if (data.sms) {
            const { sms } = data;
            this.shop.sms_attempts = sms.attempts;
            this.shop.sms_first_received = new Date(sms.firstReceived);
          }
          if (data.call) {
            const { call } = data;
            this.shop.call_attempts = call.attempts;
            this.shop.call_first_received = new Date(call.firstReceived);
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
          this.calculateCallResponseTime();
          this.twilioLoading = false;
          this.$notify(
            'success',
            'Please check the fields. If there are no value(s) it means that the the data is not existing at the moment.'
          );
        });
    },
    fetchMakeAndModel() {
      this.shop.make = '';
      this.shop.model = '';

      this.$validator
        .validate(
          'vehicle_identification_number',
          this.shop.vehicle_identification_number,
          'vehicle_identification_number'
        )
        .then((result) => {
          if (result) {
            this.vinLoading = true;
            this.hitVinApi();
          }
        });
    },
    hitVinApi() {
      this.vinLoading = true;

      this.$http
        .post('/vin', { vin: this.shop.vehicle_identification_number })
        .then(({ data }) => {
          this.shop.make = data.make;
          this.shop.model = data.model;
          this.$notify(
            'success',
            'The make and model input field has been automatically updated. Please double check it...'
          );
        })
        .catch(() => {
          this.$notify(
            'error',
            `Cant't find a make and model for this vin ${this.shop.vehicle_identification_number}`
          );
        })
        .finally(() => {
          this.vinLoading = false;
        });
    },
    formatDate(date) {
      if (date) {
        return new Date(date);
      }
      return null;
    },
    setFormData() {
      this.processingData = true;
      const rta = [
        'start_at',
        'call_first_received',
        'sms_first_received',
        'email_first_received',
        'email_second_received',
        'chat_first_received',
      ];

      const dateFormat = 'MM/DD/YYYY hh:mm a';

      this.form = new FormData();

      _forEach(this.shop, (value, key) => {
        if (rta.includes(key)) {
          value = value ? dayjs(value).format(dateFormat) : '';
        }
        value = value !== null ? value : '';
        if (key !== 'id') {
          this.form.append(key, value);
        }
      });

      const tcfields = {};
      if (this.isTrueCar) {
        _forEach(this.trueCarFields, (value, key) => {
          if (key == 'date_time_followup') {
            tcfields[key] = value ? dayjs(value).format(dateFormat) : '';
          } else {
            if (key !== 'id' || key !== 'internetshop_id') {
              value = value !== null ? value : '';
              tcfields[key] = value;
            }
          }
        });
      }

      this.form.append('tcfields', JSON.stringify(tcfields));

      if (this.temp.emailScreenshots != null) {
        this.form.append(
          'existingEmailScreenshots',
          JSON.stringify(_map(this.temp.emailScreenshots, 'id'))
        );
      }

      if (this.emailThumb.length != null) {
        // tanggalin ung _map then i foreach pa din with condition para sa update
        // tapos wag gamitin ung index gamitin is ung order key
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
      if (
        this.trueCarFields.recording != '' ||
        this.trueCarFields.recording != null
      ) {
        this.form.append('tc_recording', this.trueCarFields.recording);
      }
    },

    store() {
      this.setFormData();
      this.axios
        .post('/secret-shop-management/internet-shop', this.form, {
          headers: {
            'Content-type': 'multipart/form-data',
          },
        })
        // eslint-disable-next-line no-unused-vars
        .then((response) => {
          this.$notify('success', 'Internet shop added!');
          this.$router.push({
            name: 'InternetShop',
            params: {
              id: response.data.id,
            },
          });
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
      const id = this.$route.params.id;
      this.form.append('_method', 'PATCH');
      this.axios
        .post(`/secret-shop-management/internet-shop/${id}`, this.form, {
          headers: {
            'Content-type': 'multipart/form-data',
          },
        })
        .then(({ response }) => {
          console.log(response);
          this.$notify('success', 'Internet shop updated!');
          this.$router.push({
            name: 'InternetShop',
            params: {
              id: id,
            },
          });
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
      console.log('Save');
      this.$validator.validate('*').then((result) => {
        if (result) {
          this.processingData = true;
          if (this.isEdit) {
            this.update();
          } else {
            this.store();
          }
        } else {
          this.$notify('danger', 'Check all fields for validation!');
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
      window.close();
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
              this.shop.dealer_id = '';
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
