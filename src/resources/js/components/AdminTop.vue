<script lang="ts">
import axios from 'axios';
import router from '../router';
import {APP_URL} from '../app';

const http = axios.create({
    baseURL: APP_URL,
    withCredentials: true,
});

export default {
  data() {
    return {
      loggedIn: false,
      available: false,
      status: '',
      confirmDialog: false,
      succeedDialog: false,
      targetRoom: NaN
    };
  },
  created() {
    this.checkCredential();
    this.checkStatus();
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
    checkStatus(){
      http.get('/api/admin/getStatus')
        .then(res => {
          this.status = res.data.message;
          console.log(this.status);
      })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
        }
      });
    },
    Update() {
      http.get('/api/getStatus')
        .then(res => {
          this.status = res.data.message;
      })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
        }
      }); 

      console.log(this.status);

      if(this.status == 'Unavailable.') {
        http.post('/api/admin/updateStatus', {
          action: "toLeft",
          room_number: this.targetRoom
        })
          .then(res => {
            if(res.data.message === 'Updated that room ' + this.targetRoom + ' left.') {
              this.succeedDialog = true;
            }
        })
        .catch(err => {
          console.log(err.response);
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
          }
        });
      } else if (this.status == "Available."){
        http.post('/api/admin/updateStatus', {
          action: "toUsing",
          room_number: this.targetRoom
        })
          .then(res => {
            console.log(res.data);
            if(res.data.message === 'Updated that room ' + this.targetRoom + ' is using.') {
              this.succeedDialog = true;
            }
        })
        .catch(err => {
          console.log(err.response);
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login");
          }
        });
      }
    },
    goToManage() {
      router.push("/manage");
    }
  }
}
</script>

<template>
  <v-app>
    <v-app-bar color="blue">
      <v-app-bar-title>
        越後屋旅館 貸し切り風呂ページ 管理者画面
      </v-app-bar-title>
    </v-app-bar>
    <v-container style="padding-top: 20%; height: 80%;">
      <v-card title="現在の貸切風呂の状況" class="text-center">
      <v-card v-if="status === 'Available.'" class="bg-green-lighten-1 mt-5" title="利用しているお客様はいません" style="width: 80% font-size: large;"></v-card>
      <v-card v-else-if="status === 'Unavailable.'" class="bg-red-lighten-1 mt-5" title="お客様がご利用中です" style="height: 70%; font-size: large;"></v-card>
      <v-card v-else-if="status === 'A rooms reservation time.'" class="bg-green-lighten-1 mt-5" title="お客様がご予約されている時間です" style="width: 80% font-size: large;"></v-card>

      </v-card>
      <v-row justify="center" class="text-center" style="padding-top: 10%; margin-left: 10%; margin-right: 10%;">
        <v-btn x-large color="blue" :disabled="status == 'A rooms reservation time.'"  @click="confirmDialog = true;">お客様が入室・退出時にタグをかざし忘れてしまった場合</v-btn>
      </v-row>
      <v-row justify="center" class="text-center" style="padding-top: 2%; margin-left: 30%; margin-right: 30%;">
        <v-btn x-large color="blue" @click="goToManage()">ユーザーの管理</v-btn>
      </v-row>
    </v-container>

    <v-dialog
      v-model="confirmDialog"
      activator="#dialog-popup"
      class="text-center"
    >
      <v-sheet>
        <h2>
          入室・退出管理
        </h2>

        <p class="my-4">
          対象のお客様の部屋番号を入れてください。<br>
          適切な状態に更新します。
        </p>

        <v-text-field
          label="部屋番号を入れてください"
          type="number"
          v-model="targetRoom"></v-text-field>

        <v-btn color="green" @click="Update()" style="margin: 5%;">更新</v-btn>
        <v-btn color="red" @click="confirmDialog = false" style="margin: 5%;">キャンセル</v-btn>
      </v-sheet>
    </v-dialog>

    <v-dialog
      v-model="succeedDialog"
      class="text-center">
        <v-sheet>
          <p class="my-4">
            更新しました。
          </p>
                            
          <v-row justify="center" class="text-center" style="padding-top: 2%; margin-left: 30%; margin-right: 30%;">
            <v-btn color="blue" block @click="succeedDialog = false; confirmDialog = false; checkStatus();">閉じる</v-btn>
          </v-row>
        </v-sheet>
  </v-dialog> 
  </v-app>
</template>

<style scoped>
</style>