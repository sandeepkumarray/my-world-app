import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { LanguagesComponent } from './languages.component';
import { ViewLanguagesComponent } from './view-languages/view-languages.component';
import { EditLanguagesComponent } from './edit-languages/edit-languages.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'languages',
    },
    children: [
      {
        path: '',
        component: LanguagesComponent,
        data: {
          title: 'languages',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewLanguagesComponent,
        data: {
          title: 'View Language',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditLanguagesComponent,
        data: {
          title: 'Edit Language',
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
export class LanguagesRoutingModule {

}
