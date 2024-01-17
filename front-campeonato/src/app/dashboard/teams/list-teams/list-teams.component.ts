import { Component } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { TeamsService } from '../../../shared/teams/teams.service';

declare var $: any;
@Component({
  selector: 'app-list-teams',
  templateUrl: './list-teams.component.html',
  styleUrls: ['./list-teams.component.scss']
})
export class ListTeamsComponent {
  public teamsList: any = [];
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
    public TeamsService: TeamsService
  ) {

  }
  ngOnInit() {
    this.getTableData();
  }
  private getTableData(): void {
    this.teamsList = [];
    this.serialNumberArray = [];

    this.TeamsService.listTeams().subscribe((resp: any) => {
      console.log(resp.times);
      this.totalData = resp.times.lenght;
      resp.times.map((res: any, index: number) => {
        const serialNumber = index + 1;
        if (index >= this.skip && serialNumber <= this.limit) {

          this.teamsList.push(res);
          this.serialNumberArray.push(serialNumber);
        }
      });

      this.dataSource = new MatTableDataSource<any>(this.teamsList);
      this.calculateTotalPages(this.totalData, this.pageSize);

    })
  }

  selectRole(rol: any) {
    this.role_selected = rol;
  }

  deleteRol() {

    this.TeamsService.deleteTeams(this.role_selected.id).subscribe((resp: any) => {

      const INDEX = this.teamsList.findIndex((item: any) => item.id == this.role_selected.id);

      if (INDEX != -1) {
        this.teamsList.splice(INDEX, 1);

        $('#delete_patient').hide();
        $('#delete_patient').removeClass("show");
        $('.modal-backdrop').hide();
        $('body').removeClass();
        $('body').removeAttr("style");

        this.role_selected = null;
      }

    })
  }

  public searchData(value: any): void {
    this.dataSource.filter = value.trim().toLowerCase();
    this.teamsList = this.dataSource.filteredData;
  }

  public sortData(sort: any) {
    const data = this.teamsList.slice();

    if (!sort.active || sort.direction === '') {
      this.teamsList = data;
    } else {
      this.teamsList = data.sort((a: any, b: any) => {
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
