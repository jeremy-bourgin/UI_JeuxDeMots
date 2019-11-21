import { Component, OnInit, Input } from '@angular/core';

import { SearchService } from '../../services/search.service';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})

export class FormComponent implements OnInit {

	private term : string;

	private node : string;

	private not_out : string;

	private not_in : string;

	private nb_terms : string;

	private page : string;

	private default_is_advanced: boolean;

	@Input('is_advanced') is_advanced: boolean;

	constructor(private search_service : SearchService) { }

	ngOnInit()
	{
		let params = this.search_service.getParams();

		if (params.term)
		{
			this.term = params.term;
		}

		if (params.node)
		{
			this.node = params.node;
		}

		if (params.not_out)
		{
			this.not_out = params.not_out;
		}

		if (params.not_in)
		{
			this.not_in = params.not_in;
		}

		if (params.nb_terms)
		{
			this.nb_terms = params.nb_terms;
		}

		this.page = (params.p) ? params.p : "0";
		this.default_is_advanced = this.is_advanced;
	}

	public toggleAdvanced(): void
	{
		this.is_advanced = !this.is_advanced;
	}


}