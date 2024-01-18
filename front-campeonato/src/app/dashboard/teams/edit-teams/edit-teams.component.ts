import { Component } from '@angular/core';
import { TeamsService } from '../../../shared/teams/teams.service';
import { DataService } from 'src/app/shared/data/data.service';
import { ActivatedRoute } from '@angular/router';
import { ChampionshipService } from './../../../shared/championship/championship.service';

@Component({
  selector: 'app-edit-teams',
  templateUrl: './edit-teams.component.html',
  styleUrls: ['./edit-teams.component.scss']
})
export class EditTeamsComponent {
  sideBar: any = [];
  name = '';
  valid_form = false;
  valid_form_success = false;
  text_validation: any = null;

  public campeonato_id: any;
  public championshipList: any = [];
  role_id: any;

  constructor(
    public DataService: DataService,
    public TeamsService: TeamsService,
    public activedRoute: ActivatedRoute,
    public ChampionshipService: ChampionshipService
  ) { }

  ngOnInit() {
    this.sideBar = this.DataService.sideBar[0].menu;
    this.activedRoute.params.subscribe((resp: any) => {
      this.role_id = resp.id;
    });
    this.showRole();
  }

  showRole() {
    this.TeamsService.showTeams(this.role_id).subscribe((resp: any) => {
      console.log(resp);
      this.name = resp.name;
      this.campeonato_id = resp.campeonato_id;

    });

    this.ChampionshipService.listChampionships().subscribe(
      (resp: any) => {
        // Verifique se o array championshipsList existe nos dados retornados
        if (resp && Array.isArray(resp.campeonato)) {
          this.championshipList = resp.campeonato;
        } else {
          console.error('Array championshipsList não encontrado nos dados retornados.');
        }
      },
      (error) => {
        console.error('Erro ao obter lista de campeonatos:', error);
        // Adicione lógica de tratamento de erro conforme necessário
      }
    );
  }

  save() {
    this.valid_form = false;
    console.log('Campeonato ID:', this.campeonato_id);

    if (!this.name || !this.campeonato_id) {
      this.valid_form = true;
      return;
    }
    let data = {
      name: this.name,
      campeonato_id: this.campeonato_id,
    };

    this.valid_form_success = false;
    this.text_validation = null;

    this.TeamsService.editTeams(data, this.role_id).subscribe((resp: any) => {

      console.log(resp);

      if (resp.status == 403) {
        this.text_validation = resp.message;
      } else {
        this.name = '';
        // eslint-disable-next-line no-self-assign
        this.campeonato_id = this.campeonato_id;

        this.valid_form_success = true;

        let SIDE_BAR = this.sideBar;
        this.sideBar = [];

        setTimeout(() => {
          this.sideBar = SIDE_BAR;
        }, 50);
      }

    })
  }
}
