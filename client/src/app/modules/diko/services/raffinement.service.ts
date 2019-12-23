import { Injectable } from '@angular/core';

import { RequestHandlerService } from './request-handler.service';

@Injectable({
	providedIn: 'root'
})

export class RaffinementService
{

    constructor(private request_service: RequestHandlerService) { }

    public request(query_params: any, callback: Function, loading_listener?: string): void
    {
        this.request_service.requestGet(RequestHandlerService.services.raffinement, callback, query_params, loading_listener);
    }
}
