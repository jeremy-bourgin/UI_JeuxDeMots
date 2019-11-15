import { TestBed } from '@angular/core/testing';

import { LinkGeneratorService } from './link-generator.service';

describe('LinkGeneratorService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: LinkGeneratorService = TestBed.get(LinkGeneratorService);
    expect(service).toBeTruthy();
  });
});
