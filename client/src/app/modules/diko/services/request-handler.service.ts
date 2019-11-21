import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

import { LinkGeneratorService } from './link-generator.service';

@Injectable({
  providedIn: 'root'
})

export class RequestHandlerService
{
	public static readonly url: string = "http://lafourcade/api/";

	public static readonly services: any = {
		search_word: "search_word.php"
	};

	constructor(private http: HttpClient, private link_generator: LinkGeneratorService)
	{

	}

	public request(service_name : string, params : any, callback : Function): void
	{
		var self: RequestHandlerService = this;
		var o : any = this.makeInformations(service_name, params);
		var service : Observable<any> = this.http.get(o.action, {headers: o.headers});

		function action_callback(data: any): void {
			if (data.error) {
				alert(data.message);
				return;
			}

			if (!data.error && data.message) {
				alert(data.message);
			}

			if (callback) {
				callback(data.result);
			}
		}

		service.subscribe(action_callback);

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
		var action : string = this.link_generator.generateLink(url, params);
		/* end : service url */

		var result: any = {
			action: action,
			headers: headers,
		};

		return result;
	}

}
