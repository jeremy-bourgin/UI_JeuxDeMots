import { Component, OnInit } from '@angular/core';

import { LoadingService, Loading } from '../../services/loading.service';

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
		var not_listener: Loading.Listener = new LoadingService.Listener(this.notLoading.bind(this), this.notLoading.bind(this));
		this.loading_service.addListener(LoadingService.not_loading, not_listener);

		var listener: Loading.Listener = new LoadingService.Listener(this.loading.bind(this), this.stopLoading.bind(this));
		this.loading_service.addListener(LoadingService.default_listener, listener);

		this.stopLoading();
	}

	public notLoading(): void { }

	public loading()
	{
		this.is_loading = true;
	}

	public stopLoading()
	{
		this.is_loading = false;
	}

}
