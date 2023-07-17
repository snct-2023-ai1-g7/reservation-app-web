import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/',
    name: 'top',
    component: async () => {
      const top = await import('@/components/Top.vue');
      return top;
    }
  },
  {
    path: '/login',
    name: 'login',
    component: async () => {
      const login = await import('@/components/Login.vue');
      return login;
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
