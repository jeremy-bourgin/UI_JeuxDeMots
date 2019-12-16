import * as $ from 'jquery';

import { Component, OnInit } from '@angular/core';
import { SearchService } from '../../services/search.service';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.css']
})

export class ResultComponent implements OnInit
{


	constructor(public search_service : SearchService)
	{
	}

	ngOnInit()
	{
		this.search_service.run();
	}

	public loadPage(page: number, relation_type: any): void
	{
		var params: any;

		function callback(data: any)
		{
			relation_type.associated_relations = data.associated_relations;

		}

		params = this.search_service.getParams();
		params["p"] = page - 1;
		params["pname"] = relation_type.name;

		this.search_service.request(params, callback);

	}

	public arrowCollapse(event: any)
	{
		var e = $(event.target);

		if (e.hasClass("collapsed-arrow"))
		{
			e.removeClass("collapsed-arrow");
			e.addClass("expended-arrow");
		}
		else
		{
			e.addClass("collapsed-arrow");
			e.removeClass("expended-arrow");
		}
	}
}
