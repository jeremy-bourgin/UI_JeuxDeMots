import { Injectable } from '@angular/core';
import { RequestHandlerService } from './request-handler.service';

@Injectable({
  providedIn: 'root'
})
export class SearchService 
{

  private term : string;
	private node : string;
	private not_out : string;
	private not_in : string;
  private nb_terms : string;
  private p : string;

  private params : any;
  private data : any;

  constructor(urlSP: URLSearchParams, requestHS : RequestHandlerService) { 
    this.term = "";
    this.node = "";
    this.not_out = "";
    this.not_in = "";
    this.nb_terms = "20";
    this.p = "";

    if(urlSP.has("term"))
    {
      this.term = urlSP.get("term");
    }

    if(urlSP.has("node"))
    {
      this.node = urlSP.get("node");
    }

    if(urlSP.has("not_out"))
    {
      this.not_out = urlSP.get("not_out");
    }

    if(urlSP.has("not_in"))
    {
      this.not_in = urlSP.get("not_in");
    }

    if(urlSP.has("nb_terms"))
    {
      this.nb_terms = urlSP.get("nb_terms");
    }

    if(urlSP.has("p"))
    {
      this.p = urlSP.get("p");
    }

    let url = RequestHandlerService.services.search_word;

    this.params = {
      "term" : this.term,
      "node" : this.node,
      "not_out" : this.not_out,
      "not_in" : this.not_in,
      "nb_terms" : this.nb_terms,
      "p" : this.p
    };

    requestHS.request(url, this.params, function succes_callback(data:any) {
        this.data = data;
      }
    );

  }

  public getParams(): any
  {
	return this.params;
  }

  public getData(): any
  {
	return this.data;
  }
}
