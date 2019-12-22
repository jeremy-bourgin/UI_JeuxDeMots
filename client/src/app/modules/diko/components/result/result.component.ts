import * as $ from 'jquery';

import { Component, OnInit, QueryList, ViewChildren } from '@angular/core';
import { SearchService } from '../../services/search.service';
import { RaffinementService } from '../../services/raffinement.service';
import { LoadingService, Loading } from '../../services/loading.service';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.css']
})

export class ResultComponent implements OnInit
{
	public static readonly raffs_listener_name: string = "raff_loading_listener";

	public data: any = null;

	public relation_type_selected: any = null;

	public definition_page_size: number = 5;
	public definition_page_current : number = 1;

	public raffs: any = null;
	public raffs_keys: any = null;
	public raffs_is_loading: boolean = true;

	@ViewChildren('relation_types_view') relation_types_view: QueryList<any>;

	constructor(
		public loading_service: LoadingService,
		public search_service : SearchService,
		public raffinement_service: RaffinementService
	) {

	}

	ngOnInit()
	{
		function raff_callback(data: any)
		{
			this.raffs = data;
			this.raffs_keys = Object.keys(this.raffs);
		}

		function search_callback()
		{
			this.data = this.search_service.getData();
			this.relation_types_view.changes.subscribe(this.showFirstRelationType.bind(this));

			var temp_raff_sem = this.search_service.findRelationTypeByName("r_raff_sem");

			if (temp_raff_sem === null)
			{
				return;
			}

			var temp = [];
			var temp_container = temp_raff_sem.relations_out.data;

			for (var e of temp_container)
			{
				temp.push(e.name);
			}

			var data_raffs = {
				term: this.data.name,
				raff: temp
			};

			this.raffinement_service.request(data_raffs, raff_callback.bind(this), ResultComponent.raffs_listener_name);
		}

		function loading_raff()
		{
			this.raffs_is_loading = true;
		}

		function stop_loading_raff()
		{
			this.raffs_is_loading = false;
		}

		var loading_listener: Loading.Listener = new LoadingService.Listener(loading_raff.bind(this), stop_loading_raff.bind(this));
		this.loading_service.addListener(ResultComponent.raffs_listener_name, loading_listener);

		this.search_service.run(search_callback.bind(this));
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

	scrollDefinitions(): void
	{
		var current_scroll = $("html, body").prop('scrollTop');
		var def_offset = $('#accordion-definition').offset().top;
		
		if (current_scroll < def_offset)
		{
			return;
		}
		
		$("html, body").prop('scrollTop', def_offset);
	}
}
