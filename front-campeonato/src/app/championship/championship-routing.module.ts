import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from '../shared/gaurd/auth.guard';
import { ChampionshipComponent } from './championship.component';

const routes: Routes = [
  {
    path: '',
    component: ChampionshipComponent,
    canActivate: [AuthGuard],

    children: [
      {
        path: 'roles',
        loadChildren: () =>
          import('./roles/roles.module').then((m) => m.RolesModule),
      }

    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ChampionshipRoutingModule { }
