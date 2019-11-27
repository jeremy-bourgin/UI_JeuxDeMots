import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';

import { DikoRoutingModule } from './diko-routing.module';

import { FormComponent } from './components/form/form.component';
import { ResultComponent } from './components/result/result.component';
import { SemanticRefinementComponent } from './components/semantic-refinement/semantic-refinement.component';
import { IndexComponent } from './components/index/index.component';
import { SearchComponent } from './components/search/search.component';
import { LogoComponent } from './components/logo/logo.component';
import { LoadingComponent } from './components/loading/loading.component';

import { RequestHandlerService } from './services/request-handler.service';
import { SearchService } from './services/search.service';
import { LoadingService } from './services/loading.service';
import { MessageService } from './services/message.service';
import { ErrorMessageComponent } from './components/error-message/error-message.component';

@NgModule({
	declarations: [
		FormComponent,
		ResultComponent,
		SemanticRefinementComponent,
		IndexComponent,
		SearchComponent,
		LogoComponent,
		LoadingComponent,
		ErrorMessageComponent
	],
	imports: [
		CommonModule,
		FormsModule,
		HttpClientModule,
		DikoRoutingModule,
		NgbPaginationModule
	],
	exports: [
		LoadingComponent
	],
	providers: [
		RequestHandlerService,
		SearchService,
		LoadingService,
		MessageService
	]
})

export class DikoModule { }
