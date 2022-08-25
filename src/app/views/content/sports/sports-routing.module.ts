import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { SportsComponent } from './sports.component';
import { ViewSportsComponent } from './view-sports/view-sports.component';
import { EditSportsComponent } from './edit-sports/edit-sports.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'sports',
    },
    children: [
      {
        path: '',
        component: SportsComponent,
        data: {
          title: 'sports',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewSportsComponent,
        data: {
          title: 'View Sport',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditSportsComponent,
        data: {
          title: 'Edit Sport',
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
export class SportsRoutingModule {

}
