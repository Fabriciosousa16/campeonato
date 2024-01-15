import { DataService } from 'src/app/shared/data/data.service';
import { ChampionshipService } from './../../../shared/championship/championship.service';
import { ChampionshipComponent } from './../../championship.component';
import { Component } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { Sort } from '@angular/material/sort';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-role-championship',
  templateUrl: './edit-role-championship.component.html',
  styleUrls: ['./edit-role-championship.component.scss']
})
export class EditRoleChampionshipComponent {
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
    public ChampionshipService: ChampionshipService,
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
    this.ChampionshipService.showChampionships(this.role_id).subscribe((resp: any) => {
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

    this.ChampionshipService.editChampionships(data, this.role_id).subscribe((resp: any) => {

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
