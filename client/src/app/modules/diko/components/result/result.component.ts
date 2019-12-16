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

	public arrowCollapse(event: any, current: any, relation_types: any)
	{
		for (var e of relation_types)
		{
			if (current.id === e.id)
			{
				continue;
			}

			var temp = $("#rt_" + e.id + " header a");
			temp.addClass("collapsed-arrow");
			temp.removeClass("expended-arrow");

			console.log(temp);
		}

		var target = $(event.target);

		if (target.hasClass("collapsed-arrow"))
		{
			target.removeClass("collapsed-arrow");
			target.addClass("expended-arrow");
		}
		else
		{
			target.addClass("collapsed-arrow");
			target.removeClass("expended-arrow");
		}
	}
}
