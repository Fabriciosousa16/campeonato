import { Component } from '@angular/core';
import { TeamsService } from '../../../shared/teams/teams.service';
import { DataService } from 'src/app/shared/data/data.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-teams',
  templateUrl: './edit-teams.component.html',
  styleUrls: ['./edit-teams.component.scss']
})
export class EditTeamsComponent {
  sideBar: any = [];
  name = '';
  torneio_id = 1;
  status_id = 1;
  valid_form = false;
  valid_form_success = false;
  text_validation: any = null;

  role_id: any;
  constructor(
    public DataService: DataService,
    public TeamsService: TeamsService,
    public activedRoute: ActivatedRoute
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
    })
  }

  save() {
    this.valid_form = false;
    if (!this.name) {
      this.valid_form = true;
      return;
    }
    let data = {
      name: this.name,
      torneio_id: this.torneio_id,
      status_id: this.status_id
    };

    this.valid_form_success = false;
    this.text_validation = null;

    this.TeamsService.editTeams(data, this.role_id).subscribe((resp: any) => {

      console.log(resp);

      if (resp.status == 403) {
        this.text_validation = resp.message;
      } else {
        this.name = '';
        this.torneio_id = 1;
        this.status_id = 1;
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
