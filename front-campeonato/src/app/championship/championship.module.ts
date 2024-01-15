import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChampionshipRoutingModule } from './championship-routing.module';
import { ChampionshipComponent } from './championship.component';

import { ModalComponent } from '../core/modal/modal.component';
import { SharedModule } from '../shared/shared.module';


@NgModule({
  declarations: [
    ChampionshipComponent,
    // HeaderComponent,
    // SidebarComponent,
    //ModalComponent,
  ],
  imports: [
    CommonModule,
    ChampionshipRoutingModule,
    SharedModule,
  ]
})
export class ChampionshipModule { }
