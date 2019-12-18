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
		this.stopLoading();
		this.loading_service.setListener(this.loading.bind(this), this.stopLoading.bind(this));
	}

	public loading()
	{
		this.is_loading = true;
	}

	public stopLoading()
	{
		this.is_loading = false;
	}

}
