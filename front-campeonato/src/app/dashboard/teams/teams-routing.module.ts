import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AddTeamsComponent } from './add-teams/add-teams.component';
import { ListTeamsComponent } from './list-teams/list-teams.component';
import { EditTeamsComponent } from './edit-teams/edit-teams.component';
import { TeamsComponent } from './teams.component';

const routes: Routes = [
  {
    path: '',
    component: TeamsComponent,
    children: [
      {
        path: 'register',
        component: AddTeamsComponent
      },
      {
        path: 'list',
        component: ListTeamsComponent
      },
      {
        path: 'list/edit/:id',
        component: EditTeamsComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TeamsRoutingModule { }
