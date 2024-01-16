import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ListSimulationComponent } from './list-simulation/list-simulation.component';
import { EditSimulationComponent } from './edit-simulation/edit-simulation.component';
import { SimulationComponent } from './simulation.component';

const routes: Routes = [
  {
    path: '',
    component: SimulationComponent,
    children: [
      {
        path: 'list',
        component: ListSimulationComponent
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SimulationRoutingModule { }
