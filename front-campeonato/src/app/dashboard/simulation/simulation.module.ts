import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EditSimulationComponent } from './edit-simulation/edit-simulation.component';
import { ListSimulationComponent } from './list-simulation/list-simulation.component';
import { RouterModule } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SimulationRoutingModule } from './simulation-routing.module';
import { SimulationComponent } from './simulation.component';


@NgModule({
  declarations: [
    SimulationComponent,
    EditSimulationComponent,
    ListSimulationComponent
  ],
  imports: [
    CommonModule,
    SimulationRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule
  ]
})
export class SimulationModule { }