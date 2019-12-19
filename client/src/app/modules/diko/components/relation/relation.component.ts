import * as $ from 'jquery';

import { Component, OnInit, ElementRef, Input } from '@angular/core';
import { SearchService } from '../../services/search.service';

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

    constructor(private elRef : ElementRef, public search_service : SearchService) { }

    ngOnInit()
    {
		this.container = (this.is_out) ? this.relation_type.relations_out : this.relation_type.relations_in;
    }

	public getNbTerms(): number
	{
		var params: any = this.search_service.getParams();

		if (params.nb_terms) {
			return params.nb_terms;
		}

		return 10;
	}

	public loadPage(page: number): void
	{
		var params: any;

		function callback(data: any)
		{
			data.sorted_by = this.container.sorted_by;
			data.order = this.container.order;

			this.container = data;
			this.setOrder(this.container.sorted_by, true);
		}

		params = this.search_service.getParams();
		params.p = page - 1;
		params.pname = this.relation_type.name;
		params.pinout = (this.is_out) ? "out" : "in";

		this.search_service.request(params, callback.bind(this));
	}

	public generateWordLink(n : any): string
	{
		var params: any = this.search_service.getParams();
		params.term = n.name;

		var result: string = this.search_service.generateLink(params);

		return result;
	}

	public setOrder(column_name: string, is_column_changed: boolean): void
	{
		var other_column = (column_name === "name") ? "weight" : "name";

		var img = $(this.elRef.nativeElement.querySelector('.svg_' + column_name));
		var old_img = $(this.elRef.nativeElement.querySelector(".svg_" + other_column));

		if (is_column_changed)
		{
			img.attr('src', '/assets/svg_sort_arrow.svg');
			img.removeClass('not_sorted_arrow');
			img.addClass('sorted_arrow');
	
			old_img.attr('src', '/assets/svg_not_sort_arrow.svg');
			old_img.addClass('not_sorted_arrow');
			old_img.removeClass('sorted_arrow');
		}

		if(this.container.order === "desc")
		{
			img.removeClass('arrow_rotated');
		}
		else
		{
			img.addClass('arrow_rotated');
		}
	}

	public changeOrder(column_name: string): void
	{
		var is_column_changed = this.container.sorted_by !== column_name;

		if (is_column_changed)
		{
			if (column_name === "weight")
			{
				this.container.order = "desc";
			}
			else
			{
				this.container.order = "asc";
			}
		}
		else
		{
			this.container.order = (this.container.order === "desc") ? "asc" : "desc";
		}

		this.container.sorted_by = column_name;
		this.setOrder(column_name, is_column_changed);
	}
}
