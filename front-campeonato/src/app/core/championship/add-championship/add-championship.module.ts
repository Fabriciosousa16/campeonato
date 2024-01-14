import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AddChampionshipRoutingModule } from './add-championship-routing.module';
import { AddChampionshipComponent } from './add-championship.component';
import { SharedModule } from 'src/app/shared/shared.module';


@NgModule({
  declarations: [
    AddChampionshipComponent
  ],
  imports: [
    CommonModule,
    AddChampionshipRoutingModule,
    SharedModule
  ]
})
export class AddChampionshipModule { }
