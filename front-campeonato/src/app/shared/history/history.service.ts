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
    const URL = URL_SEVICES + "/history";
    return this.http.get(URL, { headers: headers });
  }

  listHistoryForChampionship(id: any) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/history/list/championship/" + id;
    return this.http.get(URL, { headers: headers });
  }
}
