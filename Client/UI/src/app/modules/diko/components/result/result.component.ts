import { Component, OnInit } from '@angular/core';

import { SearchService } from '../../services/search.service';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.css']
})

export class ResultComponent implements OnInit {

	private def_text_button: string;

	constructor(private search_service : SearchService) { }

	ngOnInit()
	{
		this.changeDefButton();
	}

	public changeDefButton(): void
	{
		var show_def = "Afficher les définitions";
		var hide_def = "Cacher les définitions";
		
		if (this.def_text_button === show_def)
		{
			this.def_text_button = hide_def;
		}
		else
		{
			this.def_text_button = show_def;
		}
	}
}
