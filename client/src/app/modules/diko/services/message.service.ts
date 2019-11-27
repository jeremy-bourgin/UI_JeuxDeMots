import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class MessageService
{
	private listener: any = {};

	constructor() { }

	public addListener(name: string, send: Function): void
	{
		this.listener[name] = {
			send: send
		};
	}

	public sendMessage(name: string, message: string): void
	{
		if (!this.listener[name])
		{
			return;
		}

		var o: any = this.listener[name];
		o.send(message);
	}

	public broadcastMessage(message: string): void
	{
		for (var name in this.listener)
		{
			this.sendMessage(name, message);
		}
	}
}
