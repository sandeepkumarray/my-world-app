import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { CountriesComponent } from './countries.component';
import { ViewCountriesComponent } from './view-countries/view-countries.component';
import { EditCountriesComponent } from './edit-countries/edit-countries.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'countries',
    },
    children: [
      {
        path: '',
        component: CountriesComponent,
        data: {
          title: 'countries',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewCountriesComponent,
        data: {
          title: 'View Countrie',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditCountriesComponent,
        data: {
          title: 'Edit Countrie',
        },
        canActivate: [AuthGuard]
      },
    ]
  },
];


@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CountriesRoutingModule {

}
