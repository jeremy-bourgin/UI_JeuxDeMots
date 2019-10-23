import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { DikoRoutingModule } from './diko-routing.module';

import { FormComponent } from './components/form/form.component';
import { ResultComponent } from './components/result/result.component';
import { SemanticRefinementComponent } from './components/semantic-refinement/semantic-refinement.component';
import { IndexComponent } from './components/index/index.component';
import { SearchComponent } from './components/search/search.component';


@NgModule({
	declarations: [
		FormComponent, 
		ResultComponent, 
		SemanticRefinementComponent,
		IndexComponent,
		SearchComponent
	],
	imports: [
		CommonModule, 
		FormsModule,
		DikoRoutingModule
	]
})

export class DikoModule { }
