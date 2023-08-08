<script lang="ts">
import {ref} from 'vue';
import axios from 'axios';
import router from '../router';
import {APP_URL} from '../app';

const showPassword = ref(false);
const user = ref('');
const password = ref('');
const http = axios.create({
    baseURL: APP_URL,
    withCredentials: true,
});

export default {
  data() {
    return {
      user: '',
      password: '',
      result: '',
      error: '',
      showPassword: false,
    };
  },
  created() {
      this.loginByParams();
  },
  methods: {
    async loginByParams() {
      await http.get('/sanctum/csrf-cookie');
      
      if(this.$route.query.user_id === undefined || this.$route.query.password === undefined) {
        console.log(this.$route.query); 
        return;
      }

      const response = await http.post('login', {
        user_id: this.$route.query.user_id,
        password: this.$route.query.password
      });

      if (response.status === 200 && response.data.message === 'Authenticated.') {
        const meResponse = await http.get('/api/me');
        const user_type = meResponse.data.data.user_type;
        switch(user_type) {
          case 'room':
            router.push('/');
            break;
          case 'admin':
            router.push('/admin');
            break;
        }
      }
    },
   async loginByForm(user: string, password: string) {
      try {
        await http.get('/sanctum/csrf-cookie');

    const response = await http.post('login', {
      user_id: user,
      password: password
    });

    if (response.status === 200 && response.data.message === 'Authenticated.') {
      const meResponse = await http.get('/api/me');
      const user_type = meResponse.data.data.user_type;
      switch(user_type) {
          case 'room':
            router.push('/');
            break;
          case 'admin':
            router.push('/admin');
            break;
      }
    }
    } catch (err) {
      console.error('Login error:', err)
    }
  },
}
}
</script>

<template>
  <v-app>
    <v-app-bar color="blue">
      <v-app-bar-title>ログイン</v-app-bar-title>
    </v-app-bar>
    <v-card width="400px" class="mx-auto mt-5">
      <v-card-title>
        <h1 class="display-1">ログイン</h1>
      </v-card-title>
    <v-card-text>
      <p>ご利用いただくにはログインが必要です。<br>お部屋にございますユーザー名とパスワードをお確かめの上、ログインをお願い致します。</p>
    </v-card-text>
    <v-card-text>
      <v-form>
        <v-text-field v-model="user" prepend-icon="mdi-account-key" Label="ユーザー名" />
        <v-text-field 
          v-model="password"
          v-bind:type="showPassword ? 'text' : 'password'"
          @click:append="showPassword = !showPassword"
          prepend-icon="mdi-lock" 
          append-icon="mdi-eye-off"
          Label="パスワード" />
      </v-form>
      <v-checkbox></v-checkbox>
      <v-card-actions>
        <v-btn @click="loginByForm(user, password)" class="info" color="primary">ログイン</v-btn>
      </v-card-actions>
    </v-card-text>
  </v-card>
  </v-app> 

</template>
  <style scoped>
</style>