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
		var self: ErrorMessageComponent = this;

		this.is_error = false;

		function callback(message: string)
		{
			self.is_error = true;
			self.message = message;
		}

		this.message_service.addListener("error", callback)
	}

	public close(): void
	{
		this.is_error = false;
	}
}
