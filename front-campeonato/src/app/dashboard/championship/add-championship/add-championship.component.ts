import { ChampionshipService } from '../../../shared/championship/championship.service';
import { DataService } from 'src/app/shared/data/data.service';
import { Component } from '@angular/core';

@Component({
  selector: 'app-add-championship',
  templateUrl: './add-championship.component.html',
  styleUrls: ['./add-championship.component.scss']
})
export class AddChampionshipComponent {
  sideBar: any = [];
  name = '';
  torneio_id = 1;
  status_id = 1;
  valid_form = false;
  valid_form_success = false;
  text_validation: any = null;

  constructor(
    public DataService: DataService,
    public ChampionshipService: ChampionshipService
  ) { }

  ngOnInit() {
    this.sideBar = this.DataService.sideBar[0].menu;
  }

  save() {
    this.valid_form = false;
    if (!this.name) {
      this.valid_form = true;
      return;
    }
    const data = {
      name: this.name,
      torneio_id: this.torneio_id,
      status_id: this.status_id
    };

    this.valid_form_success = false;
    this.text_validation = null;

    this.ChampionshipService.storeChampionships(data).subscribe((resp: any) => {

      console.log(resp);

      if (resp.status == 403) {
        this.text_validation = resp.message;
      } else {
        this.name = '';
        this.torneio_id = 1;
        this.status_id = 1;
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
