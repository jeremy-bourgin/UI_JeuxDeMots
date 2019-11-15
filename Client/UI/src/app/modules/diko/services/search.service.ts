import { Injectable } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

import { RequestHandlerService } from './request-handler.service';
import { LinkGeneratorService } from './link-generator.service';

@Injectable({
  providedIn: 'root'
})
export class SearchService
{

	private params : any;
	private data : any;

	constructor(private request_service: RequestHandlerService, private activated_route: ActivatedRoute, private link_generator: LinkGeneratorService)
	{
		var self : SearchService = this;

		function callback(data : any)
		{
			self.data = data;
		}

		this.data = null;
		this.params = {};

		var temp = this.activated_route.snapshot.queryParams;
		var e;

		for (var p in temp)
		{
			e = temp[p];

			if (e === "")
			{
				continue;
			}

			this.params[p] = e;
		}

		if ("submit" in this.params)
		{
			this.request(this.params, callback);
		}

	}
	
	public request(params: any, callback: Function): void
	{
		this.request_service.request(RequestHandlerService.services.search_word, params, callback);
	}

	public generateLink(params: any): string
	{
		return this.link_generator.generateLink("/search", params);
	}

	public getParams(): any
	{
		return this.params;
	}

	public getData(): any
	{
		return this.data;
	}
}
