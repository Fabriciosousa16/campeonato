import { ChampionshipService } from './../../../shared/championship/championship.service';
import { TeamsService } from './../../../shared/teams/teams.service';
import { Component } from '@angular/core';
import { DataService } from 'src/app/shared/data/data.service';

@Component({
  selector: 'app-add-teams',
  templateUrl: './add-teams.component.html',
  styleUrls: ['./add-teams.component.scss']
})
export class AddTeamsComponent {
  sideBar: any = [];
  name = '';

  valid_form = false;
  valid_form_success = false;
  text_validation: any = null;

  public campeonato_id: any;
  public championshipList: any = [];

  constructor(
    public DataService: DataService,
    public TeamsService: TeamsService,
    public ChampionshipService: ChampionshipService
  ) { }

  ngOnInit() {
    this.sideBar = this.DataService.sideBar[0].menu;

    this.ChampionshipService.listChampionships().subscribe(
      (resp: any) => {
        if (resp && Array.isArray(resp.campeonato)) {
          this.championshipList = resp.campeonato;
        } else {
          console.error('Array championshipsList não encontrado nos dados retornados.');
        }
      },
      (error) => {
        console.error('Erro ao obter lista de campeonatos:', error);
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
    const data = {
      name: this.name,
      campeonato_id: this.campeonato_id,
    };

    this.valid_form_success = false;
    this.text_validation = null;

    this.TeamsService.storeTeams(data).subscribe((resp: any) => {

      console.log(resp);

      if (resp.status == 403) {
        this.text_validation = resp.message;
      } else {
        this.name = '';
        // eslint-disable-next-line no-self-assign
        this.campeonato_id = this.campeonato_id;

        this.valid_form_success = true;

        const SIDE_BAR = this.sideBar;
        this.sideBar = [];

        setTimeout(() => {
          this.sideBar = SIDE_BAR;
        }, 50);
      }

    })
  }
}
