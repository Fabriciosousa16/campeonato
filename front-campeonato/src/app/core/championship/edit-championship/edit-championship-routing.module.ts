import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { EditChampionshipComponent } from './edit-championship.component';

const routes: Routes = [{ path: '', component: EditChampionshipComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EditChampionshipRoutingModule { }
