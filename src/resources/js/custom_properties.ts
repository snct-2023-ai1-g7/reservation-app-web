export{}

import { AxiosInstance } from "axios";
declare module 'vue' {
  interface ComponentCustomProperties {
    $api: AxiosInstance
  }
}