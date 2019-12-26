import { Injectable } from '@angular/core';

import { RequestHandlerService } from './request-handler.service';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})

export class AutocompleteService
{
    constructor(private request_service: RequestHandlerService) { }

    public request(prefix: string): Observable<any>
    {
        return this.request_service.observableRequestGet(
            RequestHandlerService.services.autocomplete,
            {autocomplete: prefix}
        );
    }
}
