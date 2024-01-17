import { Component } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { SimulationService } from 'src/app/shared/simulation/simulation.service';
import { ChampionshipService } from '../../../shared/championship/championship.service';


@Component({
  selector: 'app-list-simulations',
  templateUrl: './list-simulation.component.html',
  styleUrls: ['./list-simulation.component.scss']
})
export class ListSimulationComponent {
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

  public role_generals: any = [];
  public role_selected: any;

  constructor(
    public SimulationService: SimulationService
  ) {

  }
  ngOnInit() {
    this.getTableData();
  }
  private getTableData(): void {
    this.simulationList = [];
    this.serialNumberArray = [];

    this.SimulationService.listSimulation().subscribe((resp: any) => {
      console.log(resp.simulacao);

      // Verifique se resp.simulacao existe antes de tentar acessar seu comprimento
      if (resp && resp.simulacao && Array.isArray(resp.simulacao)) {
        this.totalData = resp.simulacao.length;

        resp.simulacao.map((res: any, index: number) => {
          const serialNumber = index + 1;
          if (index >= this.skip && serialNumber <= this.limit) {
            this.simulationList.push(res);
            this.serialNumberArray.push(serialNumber);
          }
        });

        this.dataSource = new MatTableDataSource<any>(this.simulationList);
        this.calculateTotalPages(this.totalData, this.pageSize);
      } else {
        console.error('A propriedade simulacao não existe ou não é um array válido:', resp);
        // Adicione lógica de tratamento de erro conforme necessário
      }
    });
  }




  selectRole(rol: any) {
    this.role_selected = rol;
  }

  public searchData(value: any): void {
    this.dataSource.filter = value.trim().toLowerCase();
    this.simulationList = this.dataSource.filteredData;
  }

  public sortData(sort: any) {
    const data = this.simulationList.slice();

    if (!sort.active || sort.direction === '') {
      this.simulationList = data;
    } else {
      this.simulationList = data.sort((a: any, b: any) => {
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
