import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})

export class RequestHandlerService
{
	static readonly url: string = "http://lafourcade/Server/";
	
	static readonly services: any = {
		"search_word": "search_word.php"
	};
	
	constructor(private http: HttpClient)
	{
		
	}
	
	public request(service_name : string, params : any, success_callback : Function): void
	{
		var o = this.makeInformations(service_name, params);
		var http_get: Observable<any> = this.http.get(o.action, {headers: o.headers});
	}
	
	private makeInformations(service_name : string, params: any): any {
		/* begin : header */
		var temp: any = {};

		temp["Accept"] = "application/json";
		temp["Content-Type"] = "application/json";

		var headers : HttpHeaders = new HttpHeaders(temp);
		/* end : header */

		/* begin : service url */
		var action : string = RequestHandlerService.url + RequestHandlerService.services[service_name];

		// url parameters
		var sign : string = "?";
		
		for (var p in params)
		{
			action += sign + p + "=" + params[p];
			sign = "&";
		}
		/* end : service url */

		var result: any = {
			action: action,
			headers: headers,
		};

		return result;
	}

}
