import { Injectable } from '@angular/core';
import { SearchService } from './search.service';

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
		var params = this.search_service.getParams();

		var separator

		return r;
	}


}
