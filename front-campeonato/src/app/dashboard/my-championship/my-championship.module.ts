import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ListMyChampionshipComponent } from './list-my-championship/list-my-championship.component';
import { RouterModule } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MyChampionshipRoutingModule } from './my-championship-routing.module';
import { MyChampionshipComponent } from './my-championship.component';
import { MatSelectModule } from '@angular/material/select';
import { SharedModule } from 'src/app/shared/shared.module';


@NgModule({
  declarations: [
    MyChampionshipComponent,
    ListMyChampionshipComponent
  ],
  imports: [
    CommonModule,
    MyChampionshipRoutingModule,
    SharedModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule,
    MatSelectModule
  ]
})
export class TeamsModule { }
