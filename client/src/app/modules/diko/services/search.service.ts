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
	}

	public run(callback?: Function): void
	{
		function initOrder(o: any): void
		{
			o.sorted_by = "weight";
			o.order = "desc";
		}

		function f(data : any)
		{
			this.data = data;

			for (var e of data.relation_types)
			{
				initOrder(e.relations_in);
				initOrder(e.relations_out);
			}

			if (callback)
			{
				callback();
			}
		}

		if ("submit" in this.params)
		{
			this.request(this.params, f.bind(this));
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
		return { ... this.params };
	}

	public getData(): any
	{
		return this.data;
	}
}
