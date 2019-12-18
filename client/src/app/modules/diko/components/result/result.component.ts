import * as $ from 'jquery';

import { Component, OnInit, QueryList, ViewChildren } from '@angular/core';
import { SearchService } from '../../services/search.service';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.css']
})

export class ResultComponent implements OnInit
{
	public data: any = null;

	public relation_type_selected: any = null;

	@ViewChildren('relation_types_view') relation_types_view: QueryList<any>;

	constructor(public search_service : SearchService)
	{
	}

	ngOnInit()
	{
		function callback()
		{
			this.data = this.search_service.getData();
			this.relation_types_view.changes.subscribe(this.showFirstRelationType.bind(this));
		}

		this.search_service.run(callback.bind(this));
	}

	showFirstRelationType(): void
	{
		if (this.data.relation_types.length === 0)
		{
			return;
		}

		this.showRelations(this.data.relation_types[0]);
	}
	
	public showRelations(relation_type: any): void
	{
		if (this.relation_type_selected !== null)
		{
			var old_button = $("#button_rt_" + this.relation_type_selected.id);
			var old_content = $("#content_rt_" + this.relation_type_selected.id);

			old_button.addClass('btn-primary');
			old_button.removeClass('btn-outline-primary');
			old_content.addClass('d-none');
		}

		this.relation_type_selected = relation_type;

		var new_button = $("#button_rt_" + this.relation_type_selected.id);
		var new_content = $("#content_rt_" + this.relation_type_selected.id);

		new_button.removeClass('btn-primary');
		new_button.addClass('btn-outline-primary');
		new_content.removeClass('d-none');

		$("html")[0].scrollTop = new_content.offset().top;
		$("body")[0].scrollTop = new_content.offset().top;
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
