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

  constructor(start: string, end: string, room_number: Number) {
    const startComponents = start.split(":"); 
    
    const hoursStart = parseInt(startComponents[0]);
    const minutesStart = parseInt(startComponents[1]);
    this.start = new Date();
    this.start.setHours(hoursStart);
    this.start.setMinutes(minutesStart);
    this.start.setSeconds(0);
    this.start.setMilliseconds(0);

    const endComponents = end.split(":");
    const hoursEnd = parseInt(endComponents[0]);
    const minutesEnd = parseInt(endComponents[1]);
    this.end = new Date();
    this.end.setHours(hoursEnd);
    this.end.setMinutes(minutesEnd);
    this.end.setSeconds(0);
    this.end.setMilliseconds(0);

    this.room_number = room_number;
  }
}

export default {
  data(): {
    loggedIn: boolean
    reservations: Array<Reservation>
    queue: Array<Reservation>
    confirmDialog: boolean
    successDialog: boolean
    dialogMessage: string
    } {
    return {
      loggedIn: false,
      reservations: new Array<Reservation>(),
      queue: new Array<Reservation>(), 
      confirmDialog: false,
      successDialog: false,
      dialogMessage: ""
    };
  },
  created() {
    this.checkCredential();
    this.checkReservartions();
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
    checkReservartions() {
      http.get('/api/getReserves')
        .then(res => {
          console.log(res.data.reservations);
          // @ts-ignore
          res.data.reservations.forEach(rsv => {
            console.log(rsv);
            // @ts-ignore
            this.reservations.push(new Reservation(rsv.start, rsv.end, rsv.room_number));
          });
        })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login"); 
          }
        })
    },
    changedCheckbox(reservation: Reservation) {
      if(this.queue.includes(reservation)) {
        this.queue.splice(this.queue.indexOf(reservation), 1);
      } else {
        this.queue.push(reservation);
      }
      console.log(this.queue);
    },
    Reserve() {
      this.queue.forEach(q => {
        http.post('/api/reserve', {
          start: `${q.start.getHours()}:${q.start.getMinutes()<10?'0':''}${q.start.getMinutes()}`,
          end: `${q.end.getHours()}:${q.end.getMinutes()<10?'0':''}${q.end.getMinutes()}`
        })
        .then(res => {
          if (res.status === 200) {

          }
        })
        .catch(err => {

        });
        this.successDialog = true;
      });
      
    },
    GoToTop() {
      router.push('/');
    }
  }
}
</script>

<template>
  <v-app v-if="loggedIn">
    <v-app-bar color="blue" style="max-height: 10%;">
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
                  <tr v-for="r in reservations">
                    <td>
                      {{ `${r.start.getHours()}:${r.start.getMinutes()<10?'0':''}${r.start.getMinutes()} ~ ${r.end.getHours()}:${r.end.getMinutes()<10?'0':''}${r.end.getMinutes()}` }}
                    </td>
                    <td v-if="r.room_number === null">
                      <v-checkbox color="blue" v-on:change="changedCheckbox(r)"></v-checkbox>
                    </td>
                    <td v-else>
                      <v-checkbox color="gray" v-bind:disabled="true"></v-checkbox>
                    </td>
                  </tr>
                </tbody>
              </v-data-table>
          </v-container>
        </v-card>
      </v-card>
      <v-row justify="center" class="text-center" v-bind:disabled="queue.length > 0" style="padding-top: 5%; margin-left: 50%; margin-right: 50%;">
          <v-btn x-large id="dialog-popup" color="blue">予約</v-btn>
      </v-row>
      <v-row justify="center" class="text-center" style="padding-top: 3%; margin-left: 50%; margin-right: 50%;">
          <v-btn x-large color="blue" @click="GoToTop()">戻る</v-btn>
      </v-row>
    </v-container>

    <v-dialog
      v-model="confirmDialog"
      activator="#dialog-popup"
      class="text-center"
    >
      <v-sheet>
        <h2>確認</h2>

        <p class="my-4">
          以下の時間で予約します。よろしいですか?
        </p>

        <li v-for="(q, index) in queue" class="my-4">
          {{ `${q.start.getHours()}:${q.start.getMinutes()<10?'0':''}${q.start.getMinutes()} から ${q.end.getHours()}:${q.end.getMinutes()<10?'0':''}${q.end.getMinutes()}` }}
        </li>

        <v-btn color="green" @click="Reserve()" style="margin: 5%;">予約</v-btn>
        <v-btn color="red" @click="confirmDialog = false" style="margin: 5%;">キャンセル</v-btn>
      </v-sheet>
    </v-dialog>

    <v-dialog
      v-model="successDialog"
      class="text-center">
        <v-sheet>
          <h2>予約完了</h2>
          <p class="my-4">
            予約が完了しました。
          </p>
                            
          <v-row justify="center" class="text-center" style="padding-top: 2%; margin-left: 30%; margin-right: 30%;">
            <v-btn color="blue" block @click="successDialog = false; confirmDialog = false; checkReservartions();">閉じる</v-btn>
          </v-row>
        </v-sheet>
  </v-dialog>
  </v-app>
</template>

<style scoped>
</style>