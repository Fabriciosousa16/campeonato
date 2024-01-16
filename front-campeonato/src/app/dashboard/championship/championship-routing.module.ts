import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ChampionshipComponent } from './championship.component';
import { AddChampionshipComponent } from './add-championship/add-championship.component';
import { ListChampionshipComponent } from './list-championship/list-championship.component';
import { EditChampionshipComponent } from './edit-championship/edit-championship.component';

const routes: Routes = [
  {
    path: '',
    component: ChampionshipComponent,
    children: [
      {
        path: 'register',
        component: AddChampionshipComponent
      },
      {
        path: 'list',
        component: ListChampionshipComponent
      },
      {
        path: 'list/edit/:id',
        component: EditChampionshipComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ChampionshipRoutingModule { }
