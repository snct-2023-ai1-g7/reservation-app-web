<script lang="ts">
import axios from 'axios';
import router from '../router';

const http = axios.create({
    baseURL: 'http://localhost',
    withCredentials: true,
});

export default {
  data() {
    return {
      loggedIn: false,
      available: false
    };
  },
  created() {
    this.checkCredential();
  },
  methods: {
    checkCredential() {
      http.get('/api/me')
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
          router.push("/login");
        }
      });
      this.loggedIn = true
    },
    goToReserve() {
      router.push('/reserve');
    }
  }
}
</script>

<template>
  <v-app v-if="loggedIn">
    <v-app-bar color="blue">
      <v-app-bar-title>
        越後屋旅館 貸し切り風呂ページ
      </v-app-bar-title>
    </v-app-bar>
    <v-container style="padding-top: 20%; height: 80%;">
      <v-card title="現在の貸切風呂の状況" class="text-center">
        <v-card v-if="available" class="bg-green-lighten-1 mt-5" prepend-icon="mdi-check-circle" title="ご利用いただけます" style="width: 80% font-size: large;"></v-card>
        <v-card v-else class="bg-red-lighten-1 mt-5" prepend-icon="mdi-block-helper" title="他のお客様がご利用中です" style="height: 70%; font-size: large;"></v-card>
      </v-card>
      <v-row justify="center" class="text-center" style="padding-top: 10%; margin-left: 50%; margin-right: 50%;">
        <v-btn x-large color="blue" @click="goToReserve()">予約</v-btn>
      </v-row>
    </v-container>
  </v-app>
</template>

<style scoped>
</style>