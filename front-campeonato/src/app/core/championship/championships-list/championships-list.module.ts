import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChampionshipsListRoutingModule } from './championships-list-routing.module';
import { ChampionshipsListComponent } from './championships-list.component';
import { SharedModule } from 'src/app/shared/shared.module';


@NgModule({
  declarations: [
    ChampionshipsListComponent
  ],
  imports: [
    CommonModule,
    ChampionshipsListRoutingModule,
    SharedModule
  ]
})
export class ChampionshipsListModule { }
