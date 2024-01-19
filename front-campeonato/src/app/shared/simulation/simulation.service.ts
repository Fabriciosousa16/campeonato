import { AuthService } from 'src/app/shared/auth/auth.service';

import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { URL_SEVICES } from 'src/app/config/config';

@Injectable({
  providedIn: 'root',
})
export class SimulationService {

  constructor(

    public http: HttpClient,
    public authService: AuthService,
  ) { }

  listSimulation() {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation";
    return this.http.get(URL, { headers: headers });
  }

  listMatchSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/list/match/" + id;
    return this.http.get(URL, { headers: headers });
  }

  listResultMatchSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/list/result/match/" + id;
    return this.http.get(URL, { headers: headers });
  }


  verifyExistSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/verify-exist-simulation/" + id;
    return this.http.post(URL, {}, { headers: headers });
  }

  verifyFasesSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/verify-fases-simulation/" + id;
    return this.http.post(URL, {}, { headers: headers });
  }

  drawSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/prize-draw/" + id;
    return this.http.post(URL, {}, { headers: headers });
  }

  simulateMatch(id: any) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/match/" + id;
    return this.http.put(URL, null, { headers: headers });  // Adicionado 'null' como corpo da solicitação
  }

}
