import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

import { LinkGeneratorService } from './link-generator.service';
import { LoadingService, Loading } from './loading.service';
import { MessageService } from './message.service';

import { environment } from './../../../../environments/environment';

@Injectable({
	providedIn: 'root'
})

export class RequestHandlerService
{
	private static requestCallback = class {
		private parent: RequestHandlerService;
		private loader: Loading.Loader;
		private user_callback: Function;

		constructor(parent: RequestHandlerService, loader: Loading.Loader, user_callback: Function)
		{
			this.parent = parent;
			this.loader = loader;
			this.user_callback = user_callback;

			this.loader.loading();
		}

		public requestCallback(data: any): void
		{
			this.loader.stopLoading();

			if (data.error) {
				this.errorMessage(data.message);
				return;
			}
	
			if (this.user_callback) {
				this.user_callback(data.result);
			}
		}

		public errorCallback(error: any): void
		{
			this.errorMessage("Le serveur n'a pas répondu. Vous pouvez réessayer");
		}

		private errorMessage(message: string): void
		{
			this.parent.message_service.sendMessage("error", message);
			this.loader.stopLoading();
		}
	}

	public static readonly url: string = environment.apiUrl;

	public static readonly services: any = {
		search_word: "search_word.php",
		raffinement: "raffinement.php",
		autocomplete: "autocomplete.php"
	};

	constructor(
		private http: HttpClient,
		private link_generator_service: LinkGeneratorService,
		private loading_service: LoadingService,
		private message_service: MessageService
	) {

	}

	private request(
		service: Observable<any>,
		user_callback: Function,
		loading_listener?: string
	): void {
		var loader: Loading.Loader = this.loading_service.createLoader(loading_listener);

		var request_callback: typeof RequestHandlerService.requestCallback.prototype;
		request_callback = new RequestHandlerService.requestCallback(this, loader, user_callback);

		service.subscribe(
			request_callback.requestCallback.bind(request_callback),
			request_callback.errorCallback.bind(request_callback)
		);
	}

	public observableRequestGet(
		service_name: string,
		query_params?: any,
		headers?: any,
		options?: any
	): Observable<any> {
		var info : any = this.makeGetInformations(
			service_name,
			query_params,
			headers
		);

		var options: any = this.makeOptions(options, info);
		var service : Observable<any> = this.http.get(info.action, options);
		
		return service;
	}

	public requestGet(
		service_name: string,
		user_callback: Function,
		query_params?: any,
		loading_name?: string,
		headers?: any,
		options?: any
	): void {
		var service : Observable<any> = this.observableRequestGet(
			service_name,
			query_params,
			headers,
			options
		);
		
		this.request(service, user_callback, loading_name);
	}

	public observableRequestPost(
		service_name: string,
		post_params: any,
		query_params?: any,
		headers?: any,
		options?: any
	): Observable<any> {
		var info: any = this.makePostInformations(
			service_name,
			post_params,
			query_params,
			headers
		);

		var options: any = this.makeOptions(options, info);
		var service : Observable<any> = this.http.post(info.action, info.body, options);

		return service;
	}

	public requestPost(
		service_name: string,
		user_callback: Function,
		post_params: any,
		query_params?: any,
		loading_name?: string,
		headers?: any,
		options?: any
	): void {
		var service : Observable<any> = this.observableRequestPost(
			service_name,
			post_params,
			query_params,
			headers, 
			options
		);

		this.request(service, user_callback, loading_name);
	}

	private makeOptions(options: any, info: any): any
	{
		var temp: any = info.headers;

		return {
			...
			options, 
			temp
		};
	}

	private makeInformations(
		service_name : string,
		query_params?: any,
		headers?: any
	): any {
		var temp_url = RequestHandlerService.url + service_name;
		var url : string = this.link_generator_service.generateLink(temp_url, query_params);

		var http_headers: HttpHeaders = new HttpHeaders({
			Accept: "application/json",
			...
			headers
		});

		return {
			action: url,
			headers: http_headers
		};
	}

	private makeGetInformations(
		service_name : string,
		query_params?: any,
		headers?: any
	): any {
		return this.makeInformations(service_name, query_params, headers);
	}

	private makePostInformations(
		service_name: string,
		post_params: any,
		query_params?: any,
		headers?: any
	): any {
		var post_headers: any = {
			"Content-Type": "multipart/form-data",
			...
			headers
		};

		var result = this.makeInformations(service_name, query_params, post_headers);
		var body: FormData = new FormData();
		
		for (var p in post_params)
		{
			var e = post_params[p];

			if (Array.isArray(e))
			{
				for (var array_temp of e)
				{
					body.append(p + "[]", array_temp);
				}
			}
			else if (e instanceof Object)
			{
				for (var object_temp in e)
				{
					body.append(p + "[" + object_temp +"]", e[object_temp]);
				}
			}
			else
			{
				body.append(p, e);
			}
		}
		
		return {
			body: body,
			...
			result,
		};
	}
}
