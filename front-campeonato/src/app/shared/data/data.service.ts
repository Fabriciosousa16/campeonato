import { Injectable } from '@angular/core';
import { routes } from '../routes/routes';
import { HttpClient } from '@angular/common/http';
@Injectable({
  providedIn: 'root',
})
export class DataService {
  constructor(private http: HttpClient) { }

  public sideBar = [
    {
      tittle: 'Meu Campeonato',
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
          menuValue: 'Campeonato',
          hasSubRoute: true,
          showSubRoute: false,
          base: 'championship',
          img: 'assets/img/icons/menu-icon-03.svg',
          subMenus: [
            {
              menuValue: 'Lista de Campeonatos',
              route: routes.listChampionship,
              base: routes.listChampionship,
            },
            {
              menuValue: 'Adicionar Campeonato',
              route: routes.addChampionship,
              base: routes.addChampionship,
            },
          ],
        },

        {
          menuValue: 'Times',
          hasSubRoute: true,
          showSubRoute: false,
          base: 'teams',
          img: 'assets/img/icons/menu-icon-03.svg',
          subMenus: [
            {
              menuValue: 'Lista de Times',
              route: routes.listTeams,
              base: routes.listTeams,
            },
            {
              menuValue: 'Adicionar Times',
              route: routes.addTeams,
              base: routes.addTeams,
            },
          ],
        },

        {
          menuValue: 'Simulação',
          hasSubRoute: true,
          showSubRoute: false,
          base: 'simulation',
          img: 'assets/img/icons/menu-icon-03.svg',
          subMenus: [
            {
              menuValue: 'Lista de Simulaçoes',
              route: routes.listSimulation,
              base: routes.listSimulation,
            },
          ],
        },

        {
          menuValue: 'Histórico',
          hasSubRoute: true,
          showSubRoute: false,
          base: 'history',
          img: 'assets/img/icons/menu-icon-03.svg',
          subMenus: [
            {
              menuValue: 'Histórico de Competições',
              route: routes.listHistory,
              base: routes.listHistory,
            },
          ],
        },

      ],
    },
  ];

}
