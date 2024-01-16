import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
// import { AuthGuard } from './shared/gaurd/auth.guard';

const routes: Routes = [
  {
    path: '',
    pathMatch: 'full',
    redirectTo: 'login',
  },

  {
    path: '',
    loadChildren: () => import('./core/core.module').then((m) => m.CoreModule),
  },

  {
    path: '',
    loadChildren: () =>
      import('./authentication/authentication.module').then(
        (m) => m.AuthenticationModule
      ),
  },
  {
    path: 'error',
    loadChildren: () =>
      import('./error/error.module').then((m) => m.ErrorModule),
  },

  {
    path: 'championship',
    loadChildren: () =>
      import('./dashboard/championship/championship.module').then((m) => m.ChampionshipModule),
  },

  {
    path: 'teams',
    loadChildren: () =>
      import('./dashboard/teams/teams.module').then((m) => m.TeamsModule),
  },

  {
    path: 'simulation',
    loadChildren: () =>
      import('./dashboard/simulation/simulation.module').then((m) => m.SimulationModule),
  },

  {
    path: 'history',
    loadChildren: () =>
      import('./dashboard/history/history.module').then((m) => m.HistoryModule),
  },

  {
    path: '**',
    redirectTo: 'error/error404',
    pathMatch: 'full',
  },

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule { }
