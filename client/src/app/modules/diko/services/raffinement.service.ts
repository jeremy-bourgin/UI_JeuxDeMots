import { Injectable } from '@angular/core';

import { RequestHandlerService } from './request-handler.service';

@Injectable({
	providedIn: 'root'
})

export class RaffinementService
{

    constructor(private request_service: RequestHandlerService) { }

    public request(body_params: any, callback: Function, loading_listener?: string): void
    {
        if (body_params.raff === null || body_params.raff.length === 0)
        {
            return;
        }

        this.request_service.requestPost(RequestHandlerService.services.raffinement, callback, body_params, null, loading_listener);
    }
}
