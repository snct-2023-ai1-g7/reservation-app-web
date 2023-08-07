<script lang="ts">
import axios from 'axios';
import router from '../router';
import { APP_URL } from '../app';

const http = axios.create({
    baseURL: APP_URL,
    withCredentials: true,
});

class Reservation {
  start: Date;
  end: Date;
  room_number: Number;

  constructor(start: Date, end: Date, room_number: Number) {
    this.start = start;
    this.end = end;
    this.room_number = room_number;
  }
}

export default {
  data(): {
    loggedIn: boolean
    currents: Array<Reservation>
    room_number: number
    } {
    return {
      loggedIn: false,
      currents: [],
      room_number: NaN
    };
  },
  created() {
    this.checkCredential();
    this.checkReservartions();
  },
  methods: {
    checkCredential() {
      http.get('/api/me')
        .then(res => {
          this.room_number = res.data.data.room_number;
        })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
        }
      });
      this.loggedIn = true
    },
    checkReservartions() {
      http.get('/api/getReserves')
        .then(res => {
          console.log(res.data.reservations);
          // @ts-ignore
          res.data.reservations.forEach(rsv => {
            console.log(rsv);
            // @ts-ignore
            this.currents.push(new Reservation(rsv.start, rsv.end, rsv.room_number));
          });
        })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login"); 
          }
        })
    },
    goToTop() {
      router.push('/');
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
    <v-container style="padding-top: 10%; height: 80%;">
      <v-card >
        <v-card title="予約する" class="text-center">
          <v-card-subtitle class="text-wrap">
            予約したい時間帯を選んでください。<br/>選択できない時間帯は既に他のお客様が予約されています。
          </v-card-subtitle>
          <v-container>
            <v-data-table dense style="padding-top: 10%;">
                <thead>
                  <tr>
                    <th color="blue">時間帯</th>
                    <th color="blue">予約</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="current in currents">
                    <td>
                      {{ `${current.start} ~ ${current.end}` }}
                    </td>
                    <td v-if="current.room_number === null">
                      <v-checkbox color="blue"></v-checkbox>
                    </td>
                    <td v-else>
                      <v-checkbox color="blue" v-bind:disabled="true"></v-checkbox>
                    </td>
                  </tr>
                </tbody>
              </v-data-table>
          </v-container>
        </v-card>
      </v-card>
      <v-row justify="center" class="text-center" style="padding-top: 10%; margin-left: 50%; margin-right: 50%;">
          <v-btn x-large color="blue" @click="goToTop()">戻る</v-btn>
      </v-row>
    </v-container>
  </v-app>
</template>

<style scoped>
</style>