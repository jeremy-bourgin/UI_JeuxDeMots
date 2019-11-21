import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SemanticRefinementComponent } from './semantic-refinement.component';

describe('SemanticRefinementComponent', () => {
  let component: SemanticRefinementComponent;
  let fixture: ComponentFixture<SemanticRefinementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SemanticRefinementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SemanticRefinementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
