import Vue from 'vue';
import Router from 'vue-router';
import axios from 'axios';
import { some as _some, map as _map } from 'lodash-es';

import MainLayout from './layouts/Main.vue';
import Login from './layouts/Login.vue';
import Home from './views/Home.vue';
import UserManagement from './views/UserManagement.vue';
import LmsAdmin from './views/LmsAdmin.vue';
import TemplateBuilder from './views/TemplateBuilder.vue';
import SecretshopManagement from './views/SecretshopManagement.vue';
import InternetShopForm from './views/InternetShopForm.vue';
import ClientHome from './views/ClientHome.vue';
import ClientLms from './views/ClientLms.vue';
import ClientLmsUnit from './views/ClientLmsUnit.vue';
import ModulePage from './views/ClientLmsModule.vue';
import CategoryPage from './views/ClientLmsCategoryPage.vue';
import FileManager from './views/ClientLmsFileManager.vue';
import ClientShops from './views/ClientShops.vue';
import InternetShop from './views/InternetShop.vue';
import ClientReport from './views/ClientReport.vue';
import PhoneShop from './views/PhoneShop.vue';
import GroupShop from './views/GroupShop.vue';
import ClientProfile from './views/ClientProfile.vue';
import NotFound from './views/NotFound.vue';
import ResetPasswordForm from './layouts/ResetPasswordForm.vue';
import Playlist from './views/Playlist.vue';
import QuizBuilder from './components/lms-admin/quiz-builder/QuizBuilder.vue';
import SearchResult from './views/SearchResult.vue';
import GuestLayout from './layouts/Guest.vue';
import PhoneShopGuestView from './views/Guest/Phoneshop.vue';
import InternetShopGuestView from './views/Guest/InternetShop.vue';
import GuestGroupShop from './components/secretshop/GroupShop/GuestGroupShop.vue';
import PlaylistPage from './views/PlaylistsPage.vue';
import AssignedUnits from './views/AssignedUnitsPage.vue';
import FavoritesPage from './views/FavoritesPage.vue';
import MostSearched from './views/MostSearchedPage.vue';

import store from './store/index';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/guest/phone-shop/:id?',
      name: 'PhoneShopGuestView',
      component: PhoneShopGuestView,
    },
    {
      path: '/guest/internet-shop/:id?',
      name: 'InternetShopGuestView',
      component: InternetShopGuestView,
    },
    {
      path: '/guest/group-shop/:id?',
      name: 'GroupShopGuestView',
      component: GuestGroupShop,
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: Login,
      meta: {
        auth: false,
      },
    },
    {
      path: '/login/:token?',
      component: Login,
      name: 'Login',
    },
    {
      path: '/',
      component: MainLayout,
      meta: {
        auth: true,
      },
      children: [
        {
          path: '/admin/dashboard',
          name: 'Dashboard',
          component: Home,
          icon: 'fal fa-tachometer',
          meta: {
            allowed: ['super-administrator'],
          },
        },
        {
          path: '/lms-admin',
          name: 'LMS Management',
          component: LmsAdmin,
          icon: 'fal fa-users-class',
          meta: {
            allowed: ['super-administrator'],
          },
        },
        {
          path: '/lms-admin/quiz-builder/:id?',
          name: 'Quiz Builder',
          component: QuizBuilder,
        },
        {
          path: '/lms-admin/lms-template-builder',
          name: 'LMS Template Builder',
          component: TemplateBuilder,
        },
        {
          path: '/secretshop-management',
          name: 'Secret Shop Management',
          component: SecretshopManagement,
          icon: 'fal fa-analytics',
          meta: {
            allowed: ['secret-shopper'],
          },
        },
        {
          path: '/secretshop-management/internet-shop',
          name: 'Internet Shop Form',
          component: InternetShopForm,
          meta: {
            product: 'SecretShop',
          },
        },
        {
          path: '/secretshop-management/internet-shop/:id',
          name: 'Internet Shop Form Update',
          component: InternetShopForm,
          meta: {
            product: 'SecretShop',
          },
        },
        {
          path: '/user-management',
          name: 'User Management',
          component: UserManagement,
          icon: 'fal fa-users-crown',
          meta: {
            allowed: ['super-administrator'],
          },
        },
        {
          path: '/client/profile',
          name: 'My Profile',
          component: ClientProfile,
          icon: 'fal fa-user-circle',
        },
        {
          path: '/client/profile',
          name: 'My Profile',
          component: ClientProfile,
          icon: 'fal fa-user-circle',
        },
        {
          path: '/',
          name: 'Home',
          component: ClientHome,
          icon: 'fal fa-home',
        },
        {
          path: '/client/shops',
          name: 'Secret Shops',
          component: ClientShops,
          icon: 'fal fa-user-secret',
          meta: {
            product: 'SecretShop',
          },
        },
        {
          path: '/client/lms',
          name: 'LMS',
          component: ClientLms,
          icon: 'fal fa-books',
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/client/lms/unit/:id/:playlistId?',
          name: 'UnitPage',
          component: ClientLmsUnit,
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/client/lms/module/:id',
          name: 'ModulePage',
          component: ModulePage,
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/client/reports',
          name: 'LMS Report',
          component: ClientReport,
          icon: 'fal fa-user-chart',
          meta: {
            allowed: ['account-manager', 'specific-dealer-manager'],
            product: 'LMS',
          },
        },
        {
          path: '/client/lms/category/:id',
          name: 'Category Page',
          component: CategoryPage,
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/client/lms/filemanager/',
          name: 'File Manager',
          component: FileManager,
          icon: 'fal fa-folders',
        },
        {
          path: '/client/internet-shop/:id',
          name: 'InternetShop',
          component: InternetShop,
          meta: {
            product: 'SecretShop',
          },
        },
        {
          path: '/client/phone-shops/:id',
          name: 'PhoneShop',
          component: PhoneShop,
          meta: {
            product: 'SecretShop',
          },
        },
        {
          path: '/client/group-shops/:id',
          name: 'GroupShop',
          component: GroupShop,
          meta: {
            product: 'SecretShop',
          },
        },
        {
          path: '/client/lms/playlists',
          name: 'PlaylistsPage',
          component: PlaylistPage,
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/client/lms/assigned-units',
          name: 'AssignedUnits',
          component: AssignedUnits,
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/client/lms/favorites',
          name: 'FavoritesPage',
          component: FavoritesPage,
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/client/lms/most-searched',
          name: 'MostSearched',
          component: MostSearched,
          meta: {
            product: 'LMS',
          },
        },

        {
          path: '/playlist/:id',
          name: 'Playlist',
          component: Playlist,
          meta: {
            product: 'LMS',
          },
        },
        {
          path: '/search/result',
          name: 'SearchResult',
          component: SearchResult,
        },
      ],
    },
    {
      path: '*',
      name: 'PageNotFound',
      component: NotFound,
    },
  ],
});

function showNoAcessDialog(to) {
  store.dispatch('app/toggleNoAccessDialog');
  store.dispatch('app/setNoAccessType', to.meta.product);
}

function checkToken(next, to) {
  return axios
    .post('/api/check-token', {
      token: router.app.$auth.token(),
    })
    .then(() => {
      next();
    })
    .catch(() => {
      next({
        name: `${to.name}GuestView`,
        params: {
          id: to.params.id,
        },
      });
    });
}

router.beforeEach(async (to, from, next) => {
  // this will let the user/guest view the "shops" page
  if (['InternetShop', 'PhoneShop', 'GroupShop'].includes(to.name)) {
    await checkToken(next, to);
  }

  const user = router.app.$auth.user();

  // if user is not already logged in
  // then just go "next"
  if (user === null) {
    return next();
  }

  const { roles } = user;
  const userRoles = _map(roles, (role) => role.name);

  // allow super-administrator access to all routes
  if (userRoles.includes('super-administrator')) {
    return next();
  }

  // if route has an "allowed" meta restriction
  // and the current user is not allowed, then redirect to "Home"
  if (
    to.matched.some((record) => 'allowed' in record.meta) &&
    !_some(userRoles, (role) => to.meta.allowed.includes(role))
  ) {
    next({
      name: 'Home',
    });
  }

  // this will block all specific product access based
  // on the dealer's setting
  if (to.matched.some((record) => 'product' in record.meta)) {
    if (to.meta.product === 'LMS' && user.dealer.lms_service === 0) {
      showNoAcessDialog(to);
    }

    if (
      to.meta.product === 'SecretShop' &&
      user.dealer.secretshop_service === 0
    ) {
      showNoAcessDialog(to);
    }
  }

  next();
});

Vue.router = router;

export default router;
