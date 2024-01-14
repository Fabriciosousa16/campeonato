import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AddChampionshipComponent } from './add-championship.component';

const routes: Routes = [{ path: '', component: AddChampionshipComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AddChampionshipRoutingModule { }
