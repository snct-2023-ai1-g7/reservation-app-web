<script lang="ts">
import axios from 'axios';
import router from '../router';
import { APP_URL } from '../app';
import { Reservation } from '../reservation';

const http = axios.create({
    baseURL: APP_URL,
    withCredentials: true,
});

export default {
  data(): {
    loggedIn: boolean
    reservations: Array<Reservation>
    confirmDialog: boolean
    successDialog: boolean
    dialogMessage: string
    headers: any
    roomNumber: Number
    reservationCancel: Reservation | null
    } {
    return {
      loggedIn: false,
      reservations: new Array<Reservation>(),
      confirmDialog: false,
      successDialog: false,
      dialogMessage: "",
      headers: [
        {
          text: '開始',
          value: 'start'
        },
        {
          text: '終了',
          value: 'end'
        },
        {
          text: 'キャンセル',
          value: 'cancel',
          sortable: false
        }
      ],
      roomNumber: NaN,
      reservationCancel: null
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
          this.roomNumber = res.data.room_number
        })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
        }
      });
      this.loggedIn = true
    },
    checkReservartions() {
      this.reservations = new Array<Reservation>();
      http.get('/api/getReserves')
        .then(res => {
          console.log(res.data)
          // @ts-ignore
          res.data.reservations.forEach(rsv => {
            if(rsv.room_number === this.roomNumber) {
              // @ts-ignore
              this.reservations.push(new Reservation(rsv.start, rsv.end, rsv.room_number));
            }
          });
        })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login"); 
          }
        })
    },
    CancelReservation() {
      if(this.reservationCancel == null) return;

        http.post('/api/removeReserve', {
          start: this.reservationCancel.startStr,
          end: this.reservationCancel.endStr
        })
        .then(res => {
          if (res.status === 200) {

          }
        })
        .catch(err => {

        });
        this.successDialog = true;
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
    <v-container style="padding-top: 20%; height: 80%;">
      <v-card >
        <v-card title="予約一覧" class="text-center">
          <v-card-subtitle class="text-wrap">
            お客様がご予約されている時間の一覧です。<br/>キャンセルボタンを押すと予約をキャンセルします。
          </v-card-subtitle>
          <v-container>
            <v-data-table dense style="padding-top: 10%;">
                <thead>
                  <tr>
                    <th color="blue">開始</th>
                    <th color="blue">終了</th>
                    <th>削除</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="r in reservations">
                    <td>
                      {{ r.startStr }}
                    </td>
                    <td>
                      {{ r.endStr }}
                    </td>
                    <td>
                      <v-btn x-large color="red" @click="reservationCancel = r; confirmDialog = true;">キャンセル</v-btn>
                    </td>
                  </tr>
                </tbody>
              </v-data-table>
          </v-container>
        </v-card>
      </v-card>
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
          {{ reservationCancel?.startStr }} から {{ reservationCancel?.endStr }} の予約をキャンセルしてもよろしいですか?
        </p>
       
      <v-row justify="center" class="text-center" style="padding-top: 5%;">
        <v-btn color="red" @click="CancelReservation()">キャンセル</v-btn>
      </v-row>
      <v-row justify="center" class="text-center" style="padding-top: 3%;">
        <v-btn color="green" @click="confirmDialog = false">戻る</v-btn>
      </v-row>
      </v-sheet>
    </v-dialog>

    <v-dialog
      v-model="successDialog"
      class="text-center">
        <v-sheet>
          <h2>キャンセル完了</h2>
          <p class="my-4">
            キャンセルしました。
          </p>
                            
          <v-row justify="center" class="text-center" style="padding-top: 2%; margin-left: 30%; margin-right: 30%;">
            <v-btn color="blue" block @click="checkReservartions(); successDialog = false; confirmDialog = false;">閉じる</v-btn>
          </v-row>
        </v-sheet>
  </v-dialog>
  </v-app>
</template>

<style scoped>
</style>