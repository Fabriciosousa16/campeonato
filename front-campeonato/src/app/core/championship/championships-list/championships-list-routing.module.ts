import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ChampionshipsListComponent } from './championships-list.component';

const routes: Routes = [{ path: '', component: ChampionshipsListComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ChampionshipsListRoutingModule { }
