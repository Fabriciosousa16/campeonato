import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { HistoryRoutingModule } from './history-routing.module';
import { HistoryComponent } from './history.component';
import { ListHistoryComponent } from './list-history/list-history.component';
import { RouterModule } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatSelectModule } from '@angular/material/select';


@NgModule({
  declarations: [
    HistoryComponent,
    ListHistoryComponent
  ],
  imports: [
    CommonModule,
    HistoryRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule,
    MatSelectModule
  ]
})
export class HistoryModule { }
