import { Component, OnInit } from '@angular/core';

import { LoadingService } from '../../services/loading.service';

@Component({
	selector: 'app-loading',
	templateUrl: './loading.component.html',
	styleUrls: ['./loading.component.css']
})

export class LoadingComponent implements OnInit
{
	public is_loading: boolean;

	constructor(private loading_service: LoadingService)
	{

	}

	ngOnInit()
	{
		var self: LoadingComponent = this;

		console.log("ngOnInit : ok1");

		function loading()
		{
			console.log("ok laoding");
			self.is_loading = true;
		}

		function stop_loading()
		{
			self.is_loading = false;
		}

		stop_loading();

		this.loading_service.setListener(loading, stop_loading);
	}

}
