import * as $ from 'jquery';

import { Component, OnInit, Input } from '@angular/core';
import { SearchService } from '../../services/search.service';
import { ResultComponent } from '../result/result.component';

@Component({
    selector: 'app-relation',
    templateUrl: './relation.component.html',
    styleUrls: ['./relation.component.css']
})

export class RelationComponent implements OnInit
{
	public container: any;

	@Input('relation_type') relation_type: any;
	
	@Input('is_out') is_out: boolean;

    constructor(public search_service : SearchService) { }

    ngOnInit()
    {
		this.container = (this.is_out) ? this.relation_type.relations_out : this.relation_type.relations_in;
    }

	public loadPage(page: number, relation_type: any): void
	{
		var params: any;

		function callback(data: any)
		{
			this.container = data;
		}

		params = this.search_service.getParams();
		params["p"] = page - 1;
		params["pname"] = relation_type.name;
		params["pinout"] = (this.is_out) ? "out" : "in";

		this.search_service.request(params, callback.bind(this));
	}

	public generateWordLink(n : any): string
	{
		var params: any = this.search_service.getParams();
		params.term = n.name;

		var result: string = this.search_service.generateLink(params);

		return result;
	}

	public changeOrder(column_name:string, relation_type:any): void
	{
		var other_column = (column_name === "name") ? "weight" : "name";

		var img = $('#svg_' + column_name + "_" + relation_type.id);
		var old_img = $("#svg_" + other_column + "_" + relation_type.id)

		if (relation_type.sorted_by === column_name)
		{
			relation_type.order = (relation_type.order === "desc") ? "asc" : "desc";
		}
		else
		{
			if (column_name === "weight")
			{
				relation_type.order = "desc";
			}
			else
			{
				relation_type.order = "asc";
			}

			img.attr('src', '/assets/svg_sort_arrow.svg');
			img.removeClass('not_sorted_arrow');
			img.addClass('sorted_arrow');

			old_img.attr('src', '/assets/svg_not_sort_arrow.svg');
			old_img.addClass('not_sorted_arrow');
			old_img.removeClass('sorted_arrow');
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
