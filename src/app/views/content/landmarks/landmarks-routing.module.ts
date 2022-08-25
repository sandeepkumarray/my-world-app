import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { LandmarksComponent } from './landmarks.component';
import { ViewLandmarksComponent } from './view-landmarks/view-landmarks.component';
import { EditLandmarksComponent } from './edit-landmarks/edit-landmarks.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'landmarks',
    },
    children: [
      {
        path: '',
        component: LandmarksComponent,
        data: {
          title: 'landmarks',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewLandmarksComponent,
        data: {
          title: 'View Landmark',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditLandmarksComponent,
        data: {
          title: 'Edit Landmark',
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
export class LandmarksRoutingModule {

}
