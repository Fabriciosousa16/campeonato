import { AuthService } from 'src/app/shared/auth/auth.service';

import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { URL_SEVICES } from 'src/app/config/config';

@Injectable({
  providedIn: 'root',
})
export class HistoryService {

  constructor(

    public http: HttpClient,
    public authService: AuthService,
  ) { }

  listHistory() {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/championship";
    return this.http.get(URL, { headers: headers });
  }

  showHistory(id: string) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/championship/" + id;
    return this.http.get(URL, { headers: headers });
  }

  storeHistory(data: any) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/championship";
    return this.http.post(URL, data, { headers: headers });
  }

  editHistory(data: any, id: any) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/championship/" + id;
    return this.http.put(URL, data, { headers: headers });
  }

  deleteHistory(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/championship/" + id;
    return this.http.delete(URL, { headers: headers });
  }

}
