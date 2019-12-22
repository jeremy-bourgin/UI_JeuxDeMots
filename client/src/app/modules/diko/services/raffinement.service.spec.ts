import { TestBed } from '@angular/core/testing';

import { RaffinementService } from './raffinement.service';

describe('RaffinementService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: RaffinementService = TestBed.get(RaffinementService);
    expect(service).toBeTruthy();
  });
});
