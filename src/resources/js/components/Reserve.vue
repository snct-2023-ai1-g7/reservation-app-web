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
      currents: [
        {"start": "07:00", "end": "08:00", "reserved": true},
        {"start": "08:00", "end": "09:00", "reserved": true},
        {"start": "09:00", "end": "10:00", "reserved": false},
        {"start": "10:00", "end": "11:00", "reserved": true},
        {"start": "11:00", "end": "12:00", "reserved": true},
        {"start": "12:00", "end": "13:00", "reserved": false},
        {"start": "13:00", "end": "14:00", "reserved": true},
      ]
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
                      {{ current.start + " ~ " + current.end }}
                    </td>
                    <td v-if="current.reserved">
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