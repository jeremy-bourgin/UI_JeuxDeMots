import * as $ from 'jquery';

import { Component, OnInit } from '@angular/core';
import { SearchService } from '../../services/search.service';
import { SortByPipe } from '../../pipe/SortByPipe';
import { transformAll } from '@angular/compiler/src/render3/r3_ast';
import { ReturnStatement } from '@angular/compiler';

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

	public arrowCollapse(event: any, current: any, relation_types: any): void
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

	public changeClassSortArrow(img: any, order: string)
	{

	}

	public changeOrder(column_name:string, relation_type:any): void
	{
		var other_column = (column_name === "name") ? "weight" : "name";

		var img = $('#svg_' + column_name);
		var old_img = $("#svg_" + other_column)

		if (relation_type.sorted_by === column_name)
		{
			relation_type.order = (relation_type.order === "desc") ? "asc" : "desc";
		}
		else
		{
			if (relation_type.sorted_by === "weight")
			{
				relation_type.order = "desc";
			}
			else
			{
				relation_type.order = "asc";
			}

			img.attr('src', '/assets/svg_sort_arrow.svg');
			old_img.attr('src', '/assets/svg_not_sort_arrow.svg');
		}

		relation_type.sorted_by = column_name;

		if(relation_type.order === "desc")
		{
			img.removeClass('arrow_rotated');
		}
		else
		{
			img.addClass('arrow_rotated');
		}
	}
}
