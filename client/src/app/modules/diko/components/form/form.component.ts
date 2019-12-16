import { Component, OnInit, Input } from '@angular/core';

import { SearchService } from '../../services/search.service';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})

export class FormComponent implements OnInit {

	public term : string;

	public node : string;

	public not_out : boolean;

	public not_in : boolean;

	public nb_terms : string;

	public page : number;

	public is_advanced: boolean;

	@Input('is_min') is_min: boolean;

	constructor(public search_service : SearchService) { }

	ngOnInit()
	{
		let params = this.search_service.getParams();

		if (params.submit)
		{
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
		}
		else
		{
			this.not_in = true;
		}

		this.page = (params.p) ? params.p : "0";
		this.is_advanced = false;
	}

	public toggleAdvanced(): void
	{
		this.is_advanced = !this.is_advanced;
	}
	
}
