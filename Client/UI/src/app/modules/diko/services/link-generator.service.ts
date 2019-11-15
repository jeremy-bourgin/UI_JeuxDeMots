import { Injectable } from '@angular/core';
import { SearchService } from './search-handler.service';

@Injectable({
	providedIn: 'root'
})
export class LinkGeneratorService
{

	constructor(private search_service : SearchService)
	{

	}

	public generateLink(): string
	{
		var r = "";
		var params = search_service.getParams();
		

		return r;
	}
}
