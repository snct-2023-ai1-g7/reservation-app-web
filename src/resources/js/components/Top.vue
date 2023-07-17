<script setup lang="ts">
import axios from 'axios';
import router from '../router';

const http = axios.create({
    baseURL: 'http://localhost',
    withCredentials: true,
});

// 認証が切れたらログインページに
http.get('/api/me').then((res) => {
  if(res.status == 401 && res.data.message == "Unauthenticated.") {
    router.push('/login');
  }
});
</script>

<template>
  <v-app>
    <v-card-title width="100%" class="">
      <h1 class="display-1 bg-blue text-center">越後屋旅館 貸し切り風呂ページ</h1>
    </v-card-title>

    <v-row justify="center" class="mt-5">
        <v-card width="50%" height="30%" class="text-center">
          <v-card-text>
            <h1 class="text-center">現在のご利用状況</h1>
            <v-card-text>
              <v-row align="center" class="bg-green-lighten-3 mt-5" style="height: 100px;">
                <v-col cols="3">
                  <v-icon color="green">mdi-check-circle</v-icon>
                </v-col>
                <v-vol>
                  <h1 class="text-center">ご利用いただけます</h1>
                </v-vol>
              </v-row>
            </v-card-text>
            
            
          </v-card-text>
        </v-card>
    </v-row>
    
    
  </v-app>
</template>

<style scoped>
</style>