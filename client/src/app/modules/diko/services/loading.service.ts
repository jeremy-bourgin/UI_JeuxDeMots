import { Injectable } from '@angular/core';

@Injectable({
	providedIn: 'root'
})

export class LoadingService
{
	public static Listener = class {
		private loader_callback: Function;
		private stop_loader_callback: Function;

		constructor(loader_callback: Function, stop_loader_callback: Function)
		{
			this.loader_callback = loader_callback;
			this.stop_loader_callback = stop_loader_callback;
		}

		public loader(): void
		{
			this.loader_callback();
		}

		public setLoaderCallback(loader_callback: Function): void
		{
			this.loader_callback = loader_callback;
		}

		public stopLoader(): void
		{
			this.stop_loader_callback();
		}

		public setStopLoaderCallback(stop_loader_callback: Function): void
		{
			this.stop_loader_callback = stop_loader_callback;
		}
	};

	public static Loader = class {
		private loading_service: LoadingService;
		private listener: Loading.Listener = null;
		
		constructor(loading_service: LoadingService, listener_name: string)
		{
			this.loading_service = loading_service;
			this.setListener(listener_name);
		}

		public setListener(listener_name: string): void
		{
			this.listener = this.loading_service.listeners[listener_name];
		}
	
		public loading(): void
		{
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
	
			this.listener.stopLoader();
		}
	};

	public static readonly default_listener = "default_loader";

	private listeners: any = {};

	constructor()
	{
	}

	public addListener(name: string, listener: Loading.Listener)
	{
		this.listeners[name] = listener;
	}

	public createLoader(listener_name?: string): Loading.Loader
	{
		listener_name = (listener_name) ? listener_name : LoadingService.default_listener;
		var result: Loading.Loader = new LoadingService.Loader(this, listener_name);

		return result;
	}
}

export namespace Loading
{
	export type Listener = typeof LoadingService.Listener.prototype;

	export type Loader = typeof LoadingService.Loader.prototype;
}
