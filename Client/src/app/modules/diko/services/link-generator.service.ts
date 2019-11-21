import { Injectable } from '@angular/core';

@Injectable({
	providedIn: 'root'
})
export class LinkGeneratorService
{

	constructor()
	{

	}

	public generateLink(url: string, params: any): string
	{
		var r :string = url;
		var sign : string = "?";

		for (var p in params)
		{
			r += sign + p + "=" + params[p];
			sign = "&";
		}

		return r;
	}


}
