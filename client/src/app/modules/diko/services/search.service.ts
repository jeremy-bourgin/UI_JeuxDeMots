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

	public initContainer(container: any): void
	{
		container.sorted_by = "weight";
		container.order = "desc";
	}

	public run(callback?: Function): void
	{
		function f(data : any)
		{
			this.data = data;

			for (var e of data.relation_types)
			{
				this.initContainer(e.relations_in);
				this.initContainer(e.relations_out);
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

	public request(query_params: any, callback: Function): void
	{
		this.request_service.requestGet(RequestHandlerService.services.search_word, callback, query_params);
	}

	public generateLink(query_params: any): string
	{
		return this.link_generator.generateLink("/search", query_params);
	}

	public generateWordLink(name : string): string
	{
		var params: any = this.getParams();
		params.term = name;

		var result: string = this.generateLink(params);

		return result;
	}

	public getParams(): any
	{
		return { ... this.params };
	}

	public getData(): any
	{
		return this.data;
	}

	public findRelationTypeByName(name: string): any
	{
		var i: number = 0;
		var rt: any = this.data.relation_types;
		var r: any = null;

		while (i < rt.length && r === null)
		{
			var e = rt[i];

			if (e.name === name)
			{
				r = e;
			}

			++i;
		}

		return r;
	}
}
