<script setup lang="ts">
import {ref} from 'vue';
import axios from 'axios';
import router from '../router';

const showPassword = ref(false);
const user = ref('');
const password = ref('');
const http = axios.create({
    baseURL: 'http://localhost',
    withCredentials: true,
});

function login(user: string, password: string) {
  console.log(user);
  console.log(password);
  http.get('/sanctum/csrf-cookie').then((res) => {
      console.log(res.status)
      http.post('/login', {
        user_id: user, 
        password: password
      }).then((res) => {
        console.log(res)
        if(res.status == 200 && res.data.message == "Authenticated.") {
            router.push('/');
        }
    })
  });
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
  </v-card>
  </v-app> 

</template>
  <style scoped>
</style>