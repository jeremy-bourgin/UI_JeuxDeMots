import { Routes, RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';

import { IndexComponent } from './components/index/index.component';
import { SearchComponent } from './components/search/search.component';

const routes: Routes = [
	{
		path: "",
		component: IndexComponent
	},
	{
		path: "search",
		component: SearchComponent
	}
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})

export class DikoRoutingModule {}