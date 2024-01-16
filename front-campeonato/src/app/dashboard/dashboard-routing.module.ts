import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from '../shared/gaurd/auth.guard';
import { DashboardComponent } from './dashboard.component';

const routes: Routes = [
  {
    path: '',
    component: DashboardComponent,
    canActivate: [AuthGuard],

    children: [
      {
        path: 'championship',
        loadChildren: () =>
          import('./championship/championship.module').then((m) => m.ChampionshipModule),
      },

      {
        path: 'teams',
        loadChildren: () =>
          import('./teams/teams.module').then((m) => m.TeamsModule),
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DashboardRoutingModule { }
