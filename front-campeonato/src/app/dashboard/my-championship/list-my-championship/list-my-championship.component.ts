import { Component } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { MyChampionshipService } from 'src/app/shared/my-championship/MyChampionshipservice';


@Component({
  selector: 'app-list-my-championship',
  templateUrl: './list-my-championship.component.html',
  styleUrls: ['./list-my-championship.component.scss']
})
export class ListMyChampionshipComponent {
  public simulationList: any = [];
  dataSource!: MatTableDataSource<any>;

  public showFilter = false;
  public searchDataValue = '';
  public lastIndex = 0;
  public pageSize = 10;
  public totalData = 0;
  public skip = 0;
  public limit: number = this.pageSize;
  public pageIndex = 0;
  public serialNumberArray: Array<number> = [];
  public currentPage = 1;
  public pageNumberArray: Array<number> = [];
  public pageSelection: Array<any> = [];
  public totalPages = 0;

  abertos = 0;
  emAndamento = 0;
  finalizados = 0;

  constructor(
    public MyChampionshipService: MyChampionshipService
  ) {

  }
  ngOnInit() {
    this.MyChampionshipService.listMyChampionship().subscribe((resp: any) => {
      console.log(resp.campeonatos);
      this.abertos = resp.campeonatos.abertos;
      this.emAndamento = resp.campeonatos.em_andamento;
      this.finalizados = resp.campeonatos.finalizados;

    })
  }

}
