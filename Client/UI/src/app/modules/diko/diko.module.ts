import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DikoRoutingModule } from './diko-routing.module';

import { FormComponent } from './components/form/form.component';
import { ResultComponent } from './components/result/result.component';
import { SemanticRefinementComponent } from './components/semantic-refinement/semantic-refinement.component';
import { BaseComponent } from './components/base/base.component';


@NgModule({
	declarations: [
		FormComponent, 
		ResultComponent, 
		SemanticRefinementComponent,
		BaseComponent
	],
	imports: [
		CommonModule, 
		DikoRoutingModule
	]
})

export class DikoModule { }
