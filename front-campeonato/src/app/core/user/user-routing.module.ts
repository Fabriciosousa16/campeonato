import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { UserComponent } from './user.component';

const routes: Routes = [
  {
    path: '', component: UserComponent,
    children: [
      {
        path: 'users-list',
        loadChildren: () =>
          import('./users-list/users-list.module').then(
            (m) => m.UsersListModule
          ),
      },
      {
        path: 'add-user',
        loadChildren: () =>
          import('./add-user/add-user.module').then((m) => m.AddUserModule),
      },
      {
        path: 'edit-user',
        loadChildren: () =>
          import('./edit-user/edit-user.module').then(
            (m) => m.EditUserModule
          ),
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class UserRoutingModule { }
