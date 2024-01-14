import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ChampionshipsListComponent } from './championships-list.component';

describe('ChampionshipsListComponent', () => {
  let component: ChampionshipsListComponent;
  let fixture: ComponentFixture<ChampionshipsListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ChampionshipsListComponent]
    })
      .compileComponents();

    fixture = TestBed.createComponent(ChampionshipsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
