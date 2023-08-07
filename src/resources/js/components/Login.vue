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
  methods: {
   async login(user: string, password: string) {
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
  onDecode(result: string) {
    this.result = result;
  },
  async onInit (promise: any) {
    try {
      await promise
    } catch (error: any) {
      console.log(error);
      if (error.name === 'NotAllowedError') {
          this.error = "ERROR: you need to grant camera access permisson"
        } else if (error.name === 'NotFoundError') {
          this.error = "ERROR: no camera on this device"
        } else if (error.name === 'NotSupportedError') {
          this.error = "ERROR: secure context required (HTTPS, localhost)"
        } else if (error.name === 'NotReadableError') {
          this.error = "ERROR: is the camera already in use?"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "ERROR: installed cameras are not suitable"
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.error = "ERROR: Stream API is not supported in this browser"
        }
    }
  }
}
}
</script>

<template>
  <v-app>
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
        <v-btn @click="login(user, password)" class="info" color="primary">ログイン</v-btn>
      </v-card-actions>
    </v-card-text>
    <v-card-text>
      <qrcode-stream @decode="onDecode" @init="onInit"></qrcode-stream>
    </v-card-text>
  </v-card>
  </v-app> 

</template>
  <style scoped>
</style>