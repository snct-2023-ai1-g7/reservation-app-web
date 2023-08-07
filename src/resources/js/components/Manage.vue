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
  password: string
  regenerated: boolean

  constructor(id: string, type: string, room_number: Number) {
    this.id = id;
    this.type = type;
    this.room_number = room_number;
    this.password = "";
    this.regenerated = false;
  }

  regeneratePassword() {
    http.post("/api/changePassword", {
      user_id: this.id
    })
      .then(res => {
        if(res.data.message === "Password regeneration succeeded.") {
          this.password = res.data.new_password;
          this.regenerated = true;
        }
      })
      .catch(err => {

      });
  }
}

export default {
  data(): {
    loggedIn: boolean
    users: Array<User>
    } {
    return {
      loggedIn: false,
      users: [],
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
        <v-card title="予約する" class="text-center">
          <v-card-subtitle class="text-wrap">
            予約したい時間帯を選んでください。<br/>選択できない時間帯は既に他のお客様が予約されています。
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
                  <tr v-for="user in users">
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
                      (なし)
                    </td>

                    <td v-if="user.regenerated">
                      {{ `${user.password}` }}
                    </td>
                    <td v-else>
                      <v-btn x-large color="blue" @click="user.regeneratePassword()">生成する</v-btn>            
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