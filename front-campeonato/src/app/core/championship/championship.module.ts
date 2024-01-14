import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChampionshipRoutingModule } from './championship-routing.module';
import { ChampionshipComponent } from './championship.component';


@NgModule({
  declarations: [
    ChampionshipComponent
  ],
  imports: [
    CommonModule,
    ChampionshipRoutingModule
  ]
})
export class ChampionshipModule { }
