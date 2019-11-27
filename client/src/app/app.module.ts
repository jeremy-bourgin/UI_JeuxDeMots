import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './components/app/app.component';

import { DikoModule } from './modules/diko/diko.module';

@NgModule({
	declarations: [
		AppComponent
	],
	imports: [
		BrowserModule,
		AppRoutingModule,
		DikoModule
	],
	providers: [

	],
	bootstrap: [
		AppComponent
	]
})
export class AppModule { }
