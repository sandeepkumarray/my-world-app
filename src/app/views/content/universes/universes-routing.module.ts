import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { UniversesComponent } from './universes.component';
import { ViewUniversesComponent } from './view-universes/view-universes.component';
import { EditUniversesComponent } from './edit-universes/edit-universes.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'universes',
    },
    children: [
      {
        path: '',
        component: UniversesComponent,
        data: {
          title: 'universes',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewUniversesComponent,
        data: {
          title: 'View Universe',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditUniversesComponent,
        data: {
          title: 'Edit Universe',
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
export class UniversesRoutingModule {

}
