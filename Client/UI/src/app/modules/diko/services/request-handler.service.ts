import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})

export class RequestHandlerService
{
	public static readonly url: string = "http://lafourcade/Server/";

	public static readonly services: any = {
		search_word: "search_word.php"
	};

	constructor(private http: HttpClient)
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
		var action : string = RequestHandlerService.url + service_name;

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
