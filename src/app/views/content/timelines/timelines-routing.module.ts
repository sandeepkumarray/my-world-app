import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { TimelinesComponent } from './timelines.component';
import { ViewTimelinesComponent } from './view-timelines/view-timelines.component';
import { EditTimelinesComponent } from './edit-timelines/edit-timelines.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'timelines',
    },
    children: [
      {
        path: '',
        component: TimelinesComponent,
        data: {
          title: 'timelines',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewTimelinesComponent,
        data: {
          title: 'View Timeline',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditTimelinesComponent,
        data: {
          title: 'Edit Timeline',
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
export class TimelinesRoutingModule {

}
