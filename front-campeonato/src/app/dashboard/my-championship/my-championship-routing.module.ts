import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ListMyChampionshipComponent } from './list-my-championship/list-my-championship.component';
import { MyChampionshipComponent } from './my-championship.component';

const routes: Routes = [
  {
    path: '',
    component: MyChampionshipComponent,
    children: [
      {
        path: 'list',
        component: ListMyChampionshipComponent
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MyChampionshipRoutingModule { }
