import { Component } from '@angular/core';
import { FormControl } from '@angular/forms';
import { routes } from 'src/app/shared/routes/routes';
interface data {
  value: string;
}
@Component({
  selector: 'app-edit-championship',
  templateUrl: './edit-championship.component.html',
  styleUrls: ['./edit-championship.component.scss']
})
export class EditChampionshipComponent {
  public routes = routes;
  public deleteIcon = true;
  public selectedValue!: string;
  date = new FormControl(new Date());

  deleteIconFunc() {
    this.deleteIcon = !this.deleteIcon
  }
  selectedList1: data[] = [
    { value: 'Select  Department' },
    { value: 'Orthopedics' },
    { value: 'Radiology' },
    { value: 'Dentist' },
  ];
  selectedList2: data[] = [
    { value: 'Select City' },
    { value: 'Alaska' },
    { value: 'California' },
  ];
  selectedList3: data[] = [
    { value: 'Select Country' },
    { value: 'Usa' },
    { value: 'Uk' },
    { value: 'Italy' },
  ];
  selectedList4: data[] = [
    { value: 'Select State' },
    { value: 'Alaska' },
    { value: 'California' },
  ];
}
