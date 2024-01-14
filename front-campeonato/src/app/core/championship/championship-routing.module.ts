import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ChampionshipComponent } from './championship.component';

const routes: Routes = [
  {
    path: '', component: ChampionshipComponent,
    children: [
      {
        path: 'championships-list',
        loadChildren: () =>
          import('./championships-list/championships-list.module').then(
            (m) => m.ChampionshipsListModule
          ),
      },
      {
        path: 'add-championship',
        loadChildren: () =>
          import('./add-championship/add-championship.module').then(
            (m) => m.AddChampionshipModule
          ),
      },
      {
        path: 'edit-championship',
        loadChildren: () =>
          import('./edit-championship/edit-championship.module').then(
            (m) => m.EditChampionshipModule
          ),
      },

    ]
  }

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ChampionshipRoutingModule { }
