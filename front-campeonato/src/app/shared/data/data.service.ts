import { Injectable } from '@angular/core';
import { routes } from '../routes/routes';
import { map, Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { apiResultFormat } from '../models/models';


@Injectable({
  providedIn: 'root',
})
export class DataService {
  constructor(private http: HttpClient) { }

  public getUsersList(): Observable<apiResultFormat> {
    return this.http.get<apiResultFormat>('assets/json/doctors-list.json').pipe(
      map((res: apiResultFormat) => {
        return res;
      })
    );
  }
  public getChampionshipsList(): Observable<apiResultFormat> {
    return this.http.get<apiResultFormat>('assets/json/doctors-list.json').pipe(
      map((res: apiResultFormat) => {
        return res;
      })
    );
  }
  public sideBar = [
    {
      tittle: 'Main',
      showAsTab: false,
      separateRoute: false,
      menu: [
        {
          menuValue: 'Dashboard',
          hasSubRoute: true,
          showSubRoute: false,
          base: 'dashboard',
          route: 'dashboard',
          img: 'assets/img/icons/menu-icon-01.svg',
          subMenus: [
            {
              menuValue: 'Meu Campeonato',
              route: routes.Dashboard,
              base: routes.Dashboard,
            },
          ],
        },
        {
          menuValue: 'Usuários',
          hasSubRoute: true,
          showSubRoute: false,
          base: 'doctor',
          img: 'assets/img/icons/menu-icon-02.svg',
          subMenus: [
            {
              menuValue: 'Lista de Usuários',
              route: routes.usersList,
              base: routes.usersList,
            },
            {
              menuValue: 'Adicionar Usuário',
              route: routes.addUser,
              base: routes.addUser,
            },
            {
              menuValue: 'Editar Usuário',
              route: routes.editUser,
              base: routes.editUser,
            },
          ],
        },
        {
          menuValue: 'Campeonato',
          hasSubRoute: true,
          showSubRoute: false,
          base: 'championship',
          img: 'assets/img/icons/menu-icon-03.svg',
          subMenus: [
            {
              menuValue: 'Lista de Campeonatos',
              route: routes.championshipsList,
              base: routes.championshipsList,
            },
            {
              menuValue: 'Adicionar Campeonato',
              route: routes.addChampionship,
              base: routes.addChampionship,
            },
            {
              menuValue: 'Editar Campeonato',
              route: routes.editChampionship,
              base: routes.editChampionship,
            },
          ],
        },

      ],
    },
  ];

}
