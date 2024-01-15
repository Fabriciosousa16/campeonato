import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { RolesComponent } from './roles.component';
import { AddRoleChampionshipComponent } from './add-role-championship/add-role-championship.component';
import { ListRoleChampionshipComponent } from './list-role-championship/list-role-championship.component';
import { EditRoleChampionshipComponent } from './edit-role-championship/edit-role-championship.component';

const routes: Routes = [
  {
    path: '',
    component: RolesComponent,
    children: [
      {
        path: 'register',
        component: AddRoleChampionshipComponent
      },
      {
        path: 'list',
        component: ListRoleChampionshipComponent
      },
      {
        path: 'list/edit/:id',
        component: EditRoleChampionshipComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RolesRoutingModule { }
