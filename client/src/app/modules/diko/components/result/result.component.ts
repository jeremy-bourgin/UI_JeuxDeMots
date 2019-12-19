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
		if (this.relation_type_selected !== null || this.data.relation_types.length === 0)
		{
			return;
		}

		this.showRelations(this.data.relation_types[0], false);
	}
	
	public showRelations(relation_type: any, is_scroll: boolean = true): void
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

		if (is_scroll)
		{
			$("html, body").prop('scrollTop', new_content.offset().top);
		}
	}
}
