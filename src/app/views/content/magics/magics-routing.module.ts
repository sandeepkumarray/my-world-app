import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { MagicsComponent } from './magics.component';
import { ViewMagicsComponent } from './view-magics/view-magics.component';
import { EditMagicsComponent } from './edit-magics/edit-magics.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'magics',
    },
    children: [
      {
        path: '',
        component: MagicsComponent,
        data: {
          title: 'magics',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewMagicsComponent,
        data: {
          title: 'View Magic',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditMagicsComponent,
        data: {
          title: 'Edit Magic',
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
export class MagicsRoutingModule {

}
