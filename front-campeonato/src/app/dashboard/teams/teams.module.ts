import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AddTeamsComponent } from './add-teams/add-teams.component';
import { EditTeamsComponent } from './edit-teams/edit-teams.component';
import { ListTeamsComponent } from './list-teams/list-teams.component';
import { RouterModule } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { TeamsRoutingModule } from './teams-routing.module';
import { TeamsComponent } from './teams.component';


@NgModule({
  declarations: [
    TeamsComponent,
    AddTeamsComponent,
    EditTeamsComponent,
    ListTeamsComponent
  ],
  imports: [
    CommonModule,
    TeamsRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule
  ]
})
export class TeamsModule { }
