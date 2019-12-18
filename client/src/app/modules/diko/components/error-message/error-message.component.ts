import { Component, OnInit } from '@angular/core';

import { MessageService } from '../../services/message.service';

@Component({
  selector: 'app-error-message',
  templateUrl: './error-message.component.html',
  styleUrls: ['./error-message.component.css']
})

export class ErrorMessageComponent implements OnInit {

	public is_error: boolean;

	public message: string;

	constructor(private message_service: MessageService) { }

	ngOnInit() {
		this.is_error = false;

		function callback(message: string)
		{
			this.is_error = true;
			this.message = message;
		}

		this.message_service.addListener("error", callback.bind(this))
	}

	public close(): void
	{
		this.is_error = false;
	}
}
