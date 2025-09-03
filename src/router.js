import Vue from 'vue'
import Router from 'vue-router'
import NewsView from './views/News.vue'
import ContactView from './views/Contact.vue'
import AboutView from './views/about.vue'
import ShopView from './views/Shop.vue'
import ChickenRiceDetailView from './views/Chicken rice/_id.vue'
import RegisterView from './views/Register.vue'
import LoginView from './views/Login.vue'
import AdminLoginView from './views/AdminLogin.vue'
import MainMenuView from './views/MainMenu.vue'
import WelcomeView from './views/Welcome.vue'

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
      path: '/register',
      name: 'Register',
      component: RegisterView
    },
    {
      path: '/login',
      name: 'Login',
      component: LoginView
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

// Guard: ต้องล็อกอินก่อนเข้าหน้า requiresAuth
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (localStorage.getItem('isLoggedIn') === 'true') {
      next();
    } else {
      next('/login');
    }
  } else {
    next();
  }
});

export default router
