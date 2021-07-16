<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <em class="fal fa-video-plus"></em>
        </span>
        <h3 class="kt-portlet__head-title">Video of the Day</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <form class="kt-form" ref="videoForm">
        <div class="form-group">
          <label>Name</label>
          <input
            type="text"
            class="form-control"
            placeholder="Title of the Video"
            v-model="name"
            v-validate="'required'"
            name="name"
          />
          <span
            class="kt-font-danger validate-error"
            v-show="errors.has('name')"
          >
            {{ errors.first('name') }}
          </span>
        </div>
        <div class="form-group">
          <label>Video URL</label>
          <input
            type="text"
            class="form-control"
            placeholder="Enter Video URL"
            v-model="videoUrl"
            v-validate="'required|url'"
            name="videoUrl"
          />
          <span
            class="kt-font-danger validate-error"
            v-show="errors.has('videoUrl')"
          >
            {{ errors.first('videoUrl') }}
          </span>
        </div>
        <div class="form-group">
          <label>Start Date</label>
          <VueCtkDateTimePicker
            v-model="startDate"
            no-clear-button
          ></VueCtkDateTimePicker>
        </div>
        <div class="form-group">
          <label>End Date</label>
          <VueCtkDateTimePicker
            v-model="endDate"
            no-clear-button
          ></VueCtkDateTimePicker>
        </div>
        <div class="form-group">
          <multiselect
            v-model="relatedUnit"
            :options="relatedUnits"
            :multiple="true"
            :loading="isSearchingUnits"
            :searchable="true"
            @search-change="getUnits"
            track-by="id"
            label="name"
            searchable
            placeholder="Select related units"
          >
            <template slot="singleLabel" slot-scope="{ option }">
              <strong>{{ option.name }}</strong>
            </template>
            <span slot="noOptions	">Search related units...</span>
            <span slot="noResult"
              >Oops! No elements found. Consider changing the search
              query.</span
            >
          </multiselect>
        </div>
        <div class="kt-form__actions text-center">
          <v-btn
            color="blue darken-3"
            dark
            :loading="isLoading"
            @click="submit"
          >
            Submit
          </v-btn>
          &nbsp;
          <v-btn color="grey lighten-1" dark @click="clear"> Reset </v-btn>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import { map as _map } from 'lodash-es';
import EventBus from '../../plugins/eventBus';

export default {
  name: 'VideoDay',
  data() {
    return {
      source: '',
      isLoading: false,
      isSearchingUnits: false,
      name: '',
      videoUrl: '',
      startDate: this.$currentDate,
      endDate: this.$currentDate,
      relatedUnit: null,
      relatedUnits: [],
    };
  },
  mounted() {
    this.$validator.localize('en', {
      attributes: {
        name: 'name',
        videoUrl: 'video url',
      },
    });
  },
  methods: {
    getUnits(search) {
      this.isSearchingUnits = true;
      if (this.source) {
        this.source.cancel();
      }
      this.source = this.axios.CancelToken.source();
      try {
        this.axios
          .get(`featured-video/related-units`, {
            params: {
              search,
            },
            cancelToken: this.source.token,
          })
          .then(({ data }) => {
            this.relatedUnits = data;
          });
      } catch (e) {
        // request is cancelled by the user
      }
      this.isSearchingUnits = false;
    },
    submit() {
      this.isLoading = true;
      this.$validator
        .validate('*')
        .then((result) => {
          if (result) {
            return this.send();
          }
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    send() {
      const relatedUnits = _map(this.relatedUnit, (unit) => {
        return unit.id;
      });
      const data = {
        name: this.name,
        videoUrl: this.videoUrl,
        startDate: this.startDate,
        endDate: this.endDate,
        relatedUnits,
      };
      this.$store
        .dispatch('videosDaily/addVideo', data)
        .then(() => {
          this.$store.dispatch('videosDaily/getVideos', 1);
          this.$notify('success', 'Daily video added', true);
          this.clear();
          EventBus.$emit('refreshVideoTable');
        })
        .catch((errors) => {
          this.interactWithErrorBag(errors, this.errors);
        });
    },
    clear() {
      this.$validator.reset();
      this.name = '';
      this.videoUrl = '';
      this.startDate = this.$currentDate;
      this.endDate = this.$currentDate;
      this.relatedUnit = [];
    },
  },
};
</script>
<style lang="stylus">
.vdatetime-input
  width 250px
.validate-error
  font-size 12px
</style>
