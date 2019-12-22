import { Injectable } from '@angular/core';

@Injectable({
	providedIn: 'root'
})

export class LinkGeneratorService
{

	constructor()
	{

	}

	public generateLink(url: string, query_params: any): string
	{
		var r: string = url;
		var search_params: URLSearchParams = new URLSearchParams();

		for (var p in query_params)
		{
			search_params.append(p, query_params[p]);
		}

		r += "?" + search_params.toString();

		return r;
	}
}
