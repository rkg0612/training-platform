import Vue from 'vue';
import Vuex from 'vuex';

import app from './modules/app';
import states from './modules/states';
import cities from './modules/cities';
import leadsources from './modules/leadsources';
import videosDaily from './modules/videosDaily';
import events from './modules/events';
import twilio from './modules/twilio';
import dealers from './modules/dealers';
import voiceMails from './modules/files/voiceMails';
import secretShoppers from './modules/users/secretShoppers';
import phoneNumbers from './modules/phoneNumbers';
import phoneShops from './modules/phoneShops';
import internetShops from './modules/secretshop-management/internetShops';
import courses from './modules/lms/courses';
import categories from './modules/lms/categories';
import salespersonsAndManagers from './modules/lms/salespersonsAndManagers';
import modules from './modules/lms/modules';
import units from './modules/lms/units';
import share from './modules/lms/share';
import notifications from './modules/notifications';
import secretShopSummary from './modules/secretShopSummary';
import specificDealers from './modules/specificDealers';
import secretShopsTable from './modules/secretshop-management/secretShopsTable';
import phoneShopReport from './modules/secretshop-management/phoneShopReport';
import internetShopReport from './modules/secretshop-management/internetShopReport';
import clientUnit from './modules/lms/clientUnit';
import dealerOptions from './modules/users/dealerOptions';
import users from './modules/users/users';
import playlist from './modules/playlist';
import moduleBuild from './modules/lms/moduleBuild';
import client from './modules/lms/client';
import notes from './modules/lms/notes';
import markAsCompleted from './modules/lms/markAsCompleted';
import profile from './modules/users/profile';
import relatedUnits from './modules/lms/relatedUnits';

import moduleBuilds from './modules/lms/builder/moduleBuilds';
import categoryBuilds from './modules/lms/builder/categoryBuilds';
import courseBuilds from './modules/lms/builder/courseBuilds';
import clientCategories from './modules/lms/clientCategories';
import quizBuilder from './modules/lms/builder/quizBuilder';
import guest from './modules/guest.js';
import groupShops from './modules/secretshop-management/groupShops';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    app,
    states,
    cities,
    leadsources,
    videosDaily,
    events,
    twilio,
    dealers,
    voiceMails,
    secretShoppers,
    phoneNumbers,
    phoneShops,
    internetShops,
    courses,
    categories,
    salespersonsAndManagers,
    modules,
    units,
    share,
    notifications,
    secretShopSummary,
    specificDealers,
    secretShopsTable,
    phoneShopReport,
    internetShopReport,
    moduleBuilds,
    categoryBuilds,
    courseBuilds,
    clientUnit,
    playlist,
    lmsClient: client,
    dealerOptions,
    users,
    moduleBuild,
    notes,
    markAsCompleted,
    profile,
    clientCategories,
    relatedUnits,
    quizBuilder,
    guest,
    groupShops,
  },
});

Vue.store = store;

export default store;
