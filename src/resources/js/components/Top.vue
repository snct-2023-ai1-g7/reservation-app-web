<script lang="ts">
import axios from 'axios';
import router from '../router';
import {APP_URL} from '../app';

const http = axios.create({
    baseURL: APP_URL,
    withCredentials: true,
});

export default {
  data(): {
    status: string
    loggedIn: boolean
    intervalId: any
    roomNumber: Number
  } {
    return {
      status: '',
      loggedIn: false,
      intervalId: NaN,
      roomNumber: NaN
    };
  },
  created() {
    this.checkCredential();
    this.checkStatus();
    this.intervalId = window.setInterval(this.checkStatus, 30000);
  },
  unmounted() {
       clearInterval(this.intervalId);
  },
  methods: {
    checkCredential() {
      http.get('/api/me')
        .then(res => {
          this.roomNumber = res.data.room_number;
        })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
        }
      });
      this.loggedIn = true
    },
    checkStatus(){
      http.get('/api/getStatus')
        .then(res => {
          this.status = res.data.message;
      })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
        }
      });
    },
    goToReserve() {
      router.push('/reserve');
    },
    goToReserveList() {
      router.push('/reservelist');
    }
  }
}
</script>

<template>
  <v-app>
    <v-app-bar color="blue">
      <v-app-bar-title>
        越後屋旅館 貸し切り風呂ページ
      </v-app-bar-title>
    </v-app-bar>
    <v-container style="padding-top: 20%; height: 80%;">
      <v-card title="現在の貸切風呂の状況" class="text-center">
        <v-card-subtitle>
          お客様のお部屋は {{ roomNumber }}号室です。
        </v-card-subtitle>
        <v-card v-if="status === 'Available.'" class="bg-green-lighten-1 mt-5" prepend-icon="mdi-check-circle" title="ご利用いただけます" style="width: 80% font-size: large;"></v-card>
        <v-card v-else-if="status === 'Unavailable.'" class="bg-red-lighten-1 mt-5" prepend-icon="mdi-block-helper" title="他のお客様がご利用中です" style="height: 70%; font-size: large;"></v-card>
        <v-card v-else-if="status === 'Your reservation time.'" class="bg-green-lighten-1 mt-5" prepend-icon="mdi-check-circle" title="お客様がご予約されている時間です" style="width: 80% font-size: large;"></v-card>
        <v-card v-else-if="status === 'An another rooms reservation time.'" class="bg-red-lighten-1 mt-5" prepend-icon="mdi-block-helper" title="他のお客様がご予約されている時間です" style="width: 80% font-size: large;"></v-card>
      </v-card>
      <v-row justify="center" class="text-center" style="padding-top: 10%; margin-left: 20%; margin-right: 20%;">
        <v-btn x-large color="blue" @click="goToReserve()">予約</v-btn>
      </v-row>
      <v-row justify="center" class="text-center" style="padding-top: 5%; margin-left: 20%; margin-right: 20%;">
        <v-btn x-large color="blue" @click="goToReserveList()">予約の確認・キャンセル</v-btn>
      </v-row>
    </v-container>
  </v-app>
</template>

<style scoped>
</style>