import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EditChampionshipRoutingModule } from './edit-championship-routing.module';
import { EditChampionshipComponent } from './edit-championship.component';
import { SharedModule } from 'src/app/shared/shared.module';


@NgModule({
  declarations: [
    EditChampionshipComponent
  ],
  imports: [
    CommonModule,
    EditChampionshipRoutingModule,
    SharedModule
  ]
})
export class EditChampionshipModule { }
