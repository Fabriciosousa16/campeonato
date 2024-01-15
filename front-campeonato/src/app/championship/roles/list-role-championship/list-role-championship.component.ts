import { Component, ViewChild } from '@angular/core';
import { ChampionshipService } from './../../../shared/championship/championship.service';
import { MatTableDataSource } from '@angular/material/table';

declare var $: any;
@Component({
  selector: 'app-list-championship',
  templateUrl: './list-role-championship.component.html',
  styleUrls: ['./list-role-championship.component.scss']
})
export class ListRoleChampionshipComponent {
  public championshipsList: any = [];
  dataSource!: MatTableDataSource<any>;

  @ViewChild('closebutton') closebutton: any;

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
    public ChampionshipService: ChampionshipService
  ) {

  }
  ngOnInit() {
    this.getTableData();
  }
  private getTableData(): void {
    this.championshipsList = [];
    this.serialNumberArray = [];

    this.ChampionshipService.listChampionships().subscribe((resp: any) => {
      console.log(resp.campeonato);
      this.totalData = resp.campeonato.lenght;
      resp.campeonato.map((res: any, index: number) => {
        const serialNumber = index + 1;
        if (index >= this.skip && serialNumber <= this.limit) {

          this.championshipsList.push(res);
          this.serialNumberArray.push(serialNumber);
        }
      });

      this.dataSource = new MatTableDataSource<any>(this.championshipsList);
      this.calculateTotalPages(this.totalData, this.pageSize);

    })
  }

  selectRole(rol: any) {
    this.role_selected = rol;
  }

  deleteRol() {

    this.ChampionshipService.deleteChampionships(this.role_selected.id).subscribe((resp: any) => {

      const INDEX = this.championshipsList.findIndex((item: any) => item.id == this.role_selected.id);

      if (INDEX != -1) {
        this.championshipsList.splice(INDEX, 1);

        $('#delete_patient').hide();
        $('#delete_patient').removeClass("show");
        $('.modal-backdrop').hide();
        $('body').removeClass();
        $('body').removeAttr("style");

        this.role_selected = null;
      }

    })
  }

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  public searchData(value: any): void {
    this.dataSource.filter = value.trim().toLowerCase();
    this.championshipsList = this.dataSource.filteredData;
  }

  public sortData(sort: any) {
    const data = this.championshipsList.slice();

    if (!sort.active || sort.direction === '') {
      this.championshipsList = data;
    } else {
      this.championshipsList = data.sort((a: any, b: any) => {
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
