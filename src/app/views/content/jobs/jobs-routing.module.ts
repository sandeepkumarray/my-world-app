import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { JobsComponent } from './jobs.component';
import { ViewJobsComponent } from './view-jobs/view-jobs.component';
import { EditJobsComponent } from './edit-jobs/edit-jobs.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'jobs',
    },
    children: [
      {
        path: '',
        component: JobsComponent,
        data: {
          title: 'jobs',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewJobsComponent,
        data: {
          title: 'View Job',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditJobsComponent,
        data: {
          title: 'Edit Job',
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
export class JobsRoutingModule {

}
