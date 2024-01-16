import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChampionshipRoutingModule } from './championship-routing.module';
import { ChampionshipComponent } from './championship.component';
import { AddChampionshipComponent } from './add-championship/add-championship.component';
import { EditChampionshipComponent } from './edit-championship/edit-championship.component';
import { ListChampionshipComponent } from './list-championship/list-championship.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';

@NgModule({
  declarations: [
    ChampionshipComponent,
    AddChampionshipComponent,
    EditChampionshipComponent,
    ListChampionshipComponent
  ],
  imports: [
    CommonModule,
    ChampionshipRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule
  ]
})
export class ChampionshipModule { }
