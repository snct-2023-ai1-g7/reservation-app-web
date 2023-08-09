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
      errorMessage: '',
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
      } else {
          this.errorMessage = 'ログインに失敗しました。再度QRコードを読み取りログインできるかご確認ください。\nそれでもログインできない場合はユーザー名とパスワードを入力してログインをお願い致します。'
      }
    },
   async loginByForm(user: string, password: string) {
    if(user == '' && password == '') {
      this.errorMessage = 'ユーザー名・パスワードをご入力ください。';
      return;
    } else if(user == '') {
      this.errorMessage = 'ユーザー名をご入力ください。'
      return;
    } else if(password == ''){
      this.errorMessage = 'パスワードをご入力ください。'
      return;
    }

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
      this.errorMessage = 'ログインに失敗しました。\nユーザー名とパスワードをお確かめの上再度ログインをお願い致します。'
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
    <v-container style="padding-top: 10%;">
      <v-card width="400px" class="mx-auto mt-5">
      <v-card-title>
        <h1 class="display-2">ログイン</h1>
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
        <v-card-text color="red" v-if="errorMessage.length != 0" style="white-space:pre-wrap; word-wrap:break-word;">{{ errorMessage }}</v-card-text>
          <v-card-actions>
            <v-btn color="blue" @click="loginByForm(user, password)" class="info">ログイン</v-btn>
          </v-card-actions>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app> 

</template>
  <style scoped>
</style>