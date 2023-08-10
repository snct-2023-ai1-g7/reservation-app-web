import axios from 'axios';
import { RouteRecordRaw, createRouter, createWebHistory } from 'vue-router';

const routes : Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'top',
    component: async () => {
      const top = await import('@/components/Top.vue');
      return top;
    },
    /*beforeEnter: (to, from, next) => {
      next(vm => {
        vm.$api.get('/api/me')
          .catch(err => {
            if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
              next("/login");
            }
          })
        next();
      })
    }*/
  },
  {
    path: '/admin',
    name: 'admin-top',
    component: async () => {
      const adminTop = await import('@/components/AdminTop.vue');
      return adminTop;
    }
  },
  {
    path: '/login',
    name: 'login',
    component: async () => {
      const login = await import('@/components/Login.vue');
      return login;
    }
  },
  {
    path: '/reserve',
    name: 'reserve',
    component: async () => {
      const reserve = await import('@/components/Reserve.vue');
      return reserve;
    }
  },
  {
    path: '/reservelist',
    name: 'reservelist',
    component: async () => {
      const reserveList = await import('@/components/ReserveList.vue');
      return reserveList;
    }
  },
  {
    path: '/manage',
    name: 'manage',
    component: async () => {
      const manage = await import ('@/components/Manage.vue');
      return manage;
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
