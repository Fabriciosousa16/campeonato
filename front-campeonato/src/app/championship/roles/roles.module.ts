import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { RolesRoutingModule } from './roles-routing.module';
import { RolesComponent } from './roles.component';
import { AddRoleChampionshipComponent } from './add-role-championship/add-role-championship.component';
import { EditRoleChampionshipComponent } from './edit-role-championship/edit-role-championship.component';
import { ListRoleChampionshipComponent } from './list-role-championship/list-role-championship.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';


@NgModule({
  declarations: [
    RolesComponent,
    AddRoleChampionshipComponent,
    EditRoleChampionshipComponent,
    ListRoleChampionshipComponent
  ],
  imports: [
    CommonModule,
    RolesRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule
  ]
})
export class RolesModule { }
