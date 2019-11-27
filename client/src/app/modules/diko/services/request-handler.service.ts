import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

import { LinkGeneratorService } from './link-generator.service';
import { LoadingService } from './loading.service';
import { MessageService } from './message.service';

import { environment } from './../../../../environments/environment';

@Injectable({
	providedIn: 'root'
})

export class RequestHandlerService
{
	public static readonly url: string = environment.apiUrl;

	public static readonly services: any = {
		search_word: "search_word.php"
	};

	constructor(
		private http: HttpClient,
		private link_generator_service: LinkGeneratorService,
		private loading_service: LoadingService,
		private message_service: MessageService
	) {

	}

	public request(service_name : string, params : any, callback : Function): void
	{
		var self: RequestHandlerService = this;
		var o : any = this.makeInformations(service_name, params);
		var service : Observable<any> = this.http.get(o.action, {headers: o.headers});

		function action_callback(data: any): void {
			self.loading_service.stopLoading();

			if (data.error) {
				self.message_service.sendMessage("error", data.message);
				return;
			}

			if (callback) {
				callback(data.result);
			}
		}

		service.subscribe(action_callback);
		this.loading_service.loading();
	}

	private makeInformations(service_name : string, params: any): any {
		/* begin : header */
		var temp: any = {
			Accept: "application/json"
		};

		var headers : HttpHeaders = new HttpHeaders(temp);
		/* end : header */

		/* begin : service url */
		var url : string = RequestHandlerService.url + service_name;
		var action : string = this.link_generator_service.generateLink(url, params);
		/* end : service url */

		var result: any = {
			action: action,
			headers: headers,
		};

		return result;
	}

}
