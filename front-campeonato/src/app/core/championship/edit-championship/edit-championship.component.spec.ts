import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditChampionshipComponent } from './edit-championship.component';

describe('EditChampionshipComponent', () => {
  let component: EditChampionshipComponent;
  let fixture: ComponentFixture<EditChampionshipComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [EditChampionshipComponent]
    })
      .compileComponents();

    fixture = TestBed.createComponent(EditChampionshipComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
