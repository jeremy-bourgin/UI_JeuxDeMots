import { Component, OnInit } from '@angular/core';

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

	constructor(private search_service : SearchService) { }

	ngOnInit()
	{
	}
	
	onSubmit()
	{
		console.log(this.term);
	}

}
