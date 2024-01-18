import { HistoryService } from './../../../shared/history/history.service';
import { Component } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { ChampionshipService } from './../../../shared/championship/championship.service';


@Component({
  selector: 'app-list-history',
  templateUrl: './list-history.component.html',
  styleUrls: ['./list-history.component.scss']
})
export class ListHistoryComponent {
  public historyList: any = [];
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

  public campeonato_id: any;
  public historysChampionship: any = [];

  constructor(
    public HistoryService: HistoryService,
  ) {

  }
  ngOnInit() {
    //this.getTableData();

    this.HistoryService.listHistory().subscribe(
      (resp: any) => {

        console.log(resp);

        if (resp && Array.isArray(resp.history)) {
          this.historysChampionship = resp.history;
        } else {
          console.error('Array historyList não encontrado nos dados retornados.');
        }
      },
      (error) => {
        console.error('Erro ao obter lista de campeonatos:', error);
        // Adicione lógica de tratamento de erro conforme necessário
      }
    );
  }

  private getTableData(): void {
    //
  }

  filterChampionship() {
    this.historyList = [];
    this.serialNumberArray = [];

    this.HistoryService.listHistoryForChampionship(this.campeonato_id).subscribe((resp: any) => {
      console.log(resp.result);  // Corrigir o nome da propriedade para 'historys'

      if (resp && resp.result && Array.isArray(resp.result)) {
        this.totalData = resp.result.length;

        resp.result.map((res: any, index: number) => {
          const serialNumber = index + 1;
          if (index >= this.skip && serialNumber <= this.limit) {
            this.historyList.push(res);
            this.serialNumberArray.push(serialNumber);
          }
        });

        this.dataSource = new MatTableDataSource<any>(this.historyList);
        this.calculateTotalPages(this.totalData, this.pageSize);
      } else {
        console.error('A propriedade result não existe ou não é um array válido:', resp);
        // Adicione lógica de tratamento de erro conforme necessário
      }
    });
  }


  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  public searchData(value: any): void {
    this.dataSource.filter = value.trim().toLowerCase();
    this.historyList = this.dataSource.filteredData;
  }

  public sortData(sort: any) {
    const data = this.historyList.slice();

    if (!sort.active || sort.direction === '') {
      this.historyList = data;
    } else {
      this.historyList = data.sort((a: any, b: any) => {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        const aValue = (a as any)[sort.active];
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        const bValue = (b as any)[sort.active];
        return (aValue < bValue ? -1 : 1) * (sort.direction === 'asc' ? 1 : -1);
      });
    }
  }

  public getMoreData(event: string): void {
    if (event == 'next') {
      this.currentPage++;
      this.pageIndex = this.currentPage - 1;
      this.limit += this.pageSize;
      this.skip = this.pageSize * this.pageIndex;
      this.getTableData();
    } else if (event == 'previous') {
      this.currentPage--;
      this.pageIndex = this.currentPage - 1;
      this.limit -= this.pageSize;
      this.skip = this.pageSize * this.pageIndex;
      this.getTableData();
    }
  }

  public moveToPage(pageNumber: number): void {
    this.currentPage = pageNumber;
    this.skip = this.pageSelection[pageNumber - 1].skip;
    this.limit = this.pageSelection[pageNumber - 1].limit;
    if (pageNumber > this.currentPage) {
      this.pageIndex = pageNumber - 1;
    } else if (pageNumber < this.currentPage) {
      this.pageIndex = pageNumber + 1;
    }
    this.getTableData();
  }

  public PageSize(): void {
    this.pageSelection = [];
    this.limit = this.pageSize;
    this.skip = 0;
    this.currentPage = 1;
    this.getTableData();
  }

  private calculateTotalPages(totalData: number, pageSize: number): void {
    this.pageNumberArray = [];
    this.totalPages = totalData / pageSize;
    if (this.totalPages % 1 != 0) {
      this.totalPages = Math.trunc(this.totalPages + 1);
    }
    /* eslint no-var: off */
    for (var i = 1; i <= this.totalPages; i++) {
      const limit = pageSize * i;
      const skip = limit - pageSize;
      this.pageNumberArray.push(i);
      this.pageSelection.push({ skip: skip, limit: limit });
    }
  }
}
