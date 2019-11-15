import { Injectable } from '@angular/core';
import { RequestHandlerService } from './request-handler.service';

@Injectable({
  providedIn: 'root'
})
export class SearchService
{

	private params : any;
	private data : any;

	constructor(private request_service : RequestHandlerService)
	{
		var self : SearchService = this;

		function callback(data : any)
		{
			self.data = data;
		}

		this.data = null;
		this.params = {};

		var url_params = new URLSearchParams(window.location.search);

		var params_name = [
			"term",
			"node",
			"not_out",
			"not_in",
			"nb_terms",
			"p"
		];

		var is_requested = url_params.has("submit");
		var e;
		var temp;

		for (var i in params_name)
		{
			e = params_name[i];

			if (url_params.has(e) && url_params.get(e) != "")
			{
				this.params[e] = url_params.get(e);
			}
		}

		if (is_requested)
		{
			request_service.request(RequestHandlerService.services.search_word, this.params, callback);
		}

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
