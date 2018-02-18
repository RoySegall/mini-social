import Vue from 'vue';
import Router from 'vue-router';
import Login from '@/components/Login';
import Homepage from '@/components/Homepage';

Vue.use(Router);

const routesList = [];

if (localStorage.getItem('uid') === null) {
  routesList.push({
    path: '/',
    name: 'login',
    component: Login,
  });
} else {
  routesList.push({
    path: '/',
    name: 'homepage',
    component: Homepage,
  });
}

routesList.push({
  path: '*',
  component: {
    functional: true,
    render(h) {
      return h('h1', 'Page not found!');
    },
  },
});

const router = new Router({
  mode: 'history',
  base: process.env.ROUTER_PREFIX,
  routes: routesList,
});

export default router;
