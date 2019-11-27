import { Injectable } from '@angular/core';

@Injectable({
	providedIn: 'root'
})

export class LoadingService
{
	private listener: any = null;

	constructor()
	{
	}

	public setListener(loader: Function, stop_loader: Function): void
	{
		this.listener = {
			loader: loader,
			stop_loader: stop_loader
		};

		console.log(this.listener);
	}

	public loading(): void
	{
		console.log(LoadingService.count);
		console.log(this.listener);

		if (this.listener == null)
		{
			return;
		}

		this.listener.loader();
	}

	public stopLoading(): void
	{
		if (this.listener == null)
		{
			return;
		}

		this.listener.stop_loader();
	}
}
