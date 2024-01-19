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

  // Exibir a lista de Campeonatos com status 1 ou 2
  listSimulation() {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation";
    return this.http.get(URL, { headers: headers });
  }

  //Exibir confrontos do campeonato que ainda não foram simulados
  listMatchSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/list/match/" + id;
    return this.http.get(URL, { headers: headers });
  }

  //Exibir confrontos do campeonato que ainda não foram simulados
  listResultMatchSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/list/result/match/" + id;
    return this.http.get(URL, { headers: headers });
  }


  //Nada
  showSimulation(id: string) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/" + id;
    return this.http.get(URL, { headers: headers });
  }

  //Nada
  storeSimulation(data: any) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation";
    return this.http.post(URL, data, { headers: headers });
  }

  //Nada
  editSimulation(data: any, id: any) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/" + id;
    return this.http.put(URL, data, { headers: headers });
  }

  //Verificar se a simulação selecionada, ja foi feito o sorteio

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

  //Realizar sorteio inicial
  drawSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/prize-draw/" + id;
    return this.http.post(URL, {}, { headers: headers });
  }

  // Simular Partidas do campeonato
  simulateMatch(id: any) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/match/" + id;
    return this.http.put(URL, null, { headers: headers });  // Adicionado 'null' como corpo da solicitação
  }

  deleteSimulation(id: number) {
    const headers = new HttpHeaders({ "Authorization": 'Bearer ' + this.authService.token });
    const URL = URL_SEVICES + "/simulation/" + id;
    return this.http.delete(URL, { headers: headers });
  }

}
