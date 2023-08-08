<script lang="ts">
import axios from 'axios';
import router from '../router';
import { APP_URL } from '../app';

const http = axios.create({
    baseURL: APP_URL,
    withCredentials: true,
});

class User {
  id: string;
  type: string;
  room_number: Number;
  password: string;
  regenerated: boolean;
  showDialog: boolean;

  constructor(id: string, type: string, room_number: Number) {
    this.id = id;
    this.type = type;
    this.room_number = room_number;
    this.password = "";
    this.regenerated = false;
    this.showDialog = false;
  }

  regeneratePassword() {
    http.post("/api/changePassword", {
      user_id: this.id
    })
      .then(res => {
        if(res.data.message === "Password regeneration succeeded.") {
          console.log(res.data);
          this.password = res.data.new_password;
          this.regenerated = true;
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  toJson = () => {
    return {
      user_id: this.id,
      password: this.password
    }
  }
}

export default {
  data(): {
    loggedIn: boolean
    users: Array<User>
    showDialog: boolean
    currentUser: User | null
    appUrl: string
    } {
    return {
      loggedIn: false,
      users: [],
      showDialog: false,
      currentUser: null,
      appUrl: APP_URL
    };
  },
  created() {
    this.checkCredential();
    this.getUsers();
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
    getUsers() {
      http.get('/api/getUsers')
        .then(res => {
          // userの型が決定できないのでコンパイラーの警告を無視
          // @ts-ignore
          res.data.users.forEach(user => {
            // @ts-ignore
            this.users.push(new User(user.user_id, user.user_type, user.room_number));
          });
        })
        .catch(err => {
          if(axios.isAxiosError(err) && err.response && err.response.status === 401) {
            router.push("/login"); 
          }
        })
    },
    onUpdateBtn(user: User | null) {
      console.log(user);
      this.currentUser = user;
      this.currentUser?.regeneratePassword();
      this.showDialog = true;
    },
    downloadQr(user: User | null) {
      const canvas = document.getElementById('qr') as HTMLCanvasElement;
      const link = document.createElement('a');
      link.href = canvas?.toDataURL();
      link.download = user?.id + '-QRコード.png';
      link.click();
    },
    goToTop() {
      router.push('/admin');
    }
  }
}
</script>

<template>
  <v-app v-if="loggedIn">
    <v-app-bar color="blue">
      <v-app-bar-title>
        越後屋旅館 貸し切り風呂ページ 管理者画面
      </v-app-bar-title>
    </v-app-bar>
    <v-container style="padding-top: 10%; height: 80%;">
      <v-card >
        <v-card title="ユーザーを管理する" class="text-center">
        <v-card-subtitle class="text-wrap">
         「更新」ボタンを押すと新しいパスワードとQRコードが表示されます。
        </v-card-subtitle>
        <v-container>
          <v-data-table dense style="padding-top: 10%;">
            <thead>
              <tr>
                <th color="blue">ユーザー名</th>
                <th color="blue">ユーザータイプ</th>
                <th color="blue">部屋番号</th>
                <th color="blue">パスワード</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" style="padding-top: 10%;">
                <td>
                  {{ `${user.id}` }}
                </td>

                <td v-if="user.type === 'room'">
                  お客様
                </td>
                <td v-else-if="user.type === 'admin'">
                  従業員
                </td>
                <td v-else-if="user.type === 'guest'">
                  展示用
                </td>

                <td v-if="user.room_number !== null">
                  {{ `${user.room_number}` }}
                </td>
                <td v-else>
                  なし
                </td>

                <td >
                  <v-btn x-large color="blue" @click.stop="onUpdateBtn(user)">更新</v-btn>
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

  <v-dialog
      v-model="showDialog"
      activator="parent"
      class="text-center">
        <v-sheet>
          <h2>QRコード</h2>
          <vue-qrcode id="qr" :value="`${appUrl}/login?user_id=${currentUser?.id}&password=${currentUser?.password}`" :options="{width: 200}"></vue-qrcode>
          <p class="my-4">
            パスワード: {{ `${currentUser?.password}` }} <br>
            QRコードを紛失すると、再度更新するまでログインできませんのでご注意ください。
          </p>
                            
          <v-row justify="center" class="text-center" style="margin-left: 30%; margin-right: 30%;">
            <v-btn color="blue" block @click="downloadQr(currentUser)">QRコードを保存</v-btn> 
          </v-row>
          <v-row justify="center" class="text-center" style="padding-top: 2%; margin-left: 30%; margin-right: 30%;">
            <v-btn color="blue" block @click="showDialog = false">閉じる</v-btn>
          </v-row>
        </v-sheet>
  </v-dialog>
</v-app>
</template>

<style scoped>
</style>