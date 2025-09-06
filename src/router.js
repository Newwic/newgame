import Vue from 'vue'
import Router from 'vue-router'
import NewsView from './views/News.vue'
import ContactView from './views/Contact.vue'
import AboutView from './views/about.vue'
import ShopView from './views/ShopView.vue'
import ChickenRiceDetailView from './views/Chicken rice/_id.vue'
import RegisterView from './views/Register.vue'
import LoginView from './views/Login.vue'
import AdminLoginView from './views/AdminLogin.vue'
import MainMenuView from './views/MainMenu.vue'
import WelcomeView from './views/Welcome.vue'
import ProfileView from './views/Profile.vue' // Import ProfileView

import store from './store';

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Welcome',
      component: WelcomeView
    },
    {
      path: '/profile',
      name: 'Profile',
      component: ProfileView,
      meta: { requiresAuth: true } // Protect this route
    },
    {
      path: '/register',
      name: 'Register',
      component: RegisterView,
      meta: { isGuest: true }
    },
    {
      path: '/login',
      name: 'Login',
      component: LoginView,
      meta: { isGuest: true }
    },
    {
      path: '/main-menu',
      name: 'MainMenu',
      component: MainMenuView,
      meta: { requiresAuth: true }
    },
    {
      path: '/shop',
      name: 'Shop',
      component: ShopView,
      meta: { requiresAuth: true }
    },
    {
      path: '/news',
      name: 'News',
      component: NewsView,
      meta: { requiresAuth: true }
    },
    {
      path: '/contact',
      name: 'Contact',
      component: ContactView,
      meta: { requiresAuth: true }
    },
    {
      path: '/about',
      name: 'About',
      component: AboutView,
      meta: { requiresAuth: true }
    },
    {
      path: '/chicken-rice/:id',
      name: 'ChickenRiceDetail',
      component: ChickenRiceDetailView
    },
    {
      path: '/admin-login',
      name: 'AdminLogin',
      component: AdminLoginView
    }
  ]
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const isAuthenticated = store.getters['auth/isAuthenticated'];
  const isGuestRoute = to.matched.some(record => record.meta.isGuest);

  if (requiresAuth && !isAuthenticated) {
    // If the route requires authentication and the user is not logged in,
    // redirect to the login page.
    next('/login');
  } else if (isGuestRoute && isAuthenticated) {
    // If the user is logged in and tries to access a guest-only route (like login/register),
    // redirect them to the main menu.
    next('/main-menu');
  } else {
    // Otherwise, allow navigation.
    next();
  }
});

export default router