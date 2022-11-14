import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { PlanComponent } from './plan.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: '',
    },
    children: [
      {
        path: 'subscription',
        component: PlanComponent,
        data: {
          title: 'Subscription',
        },
        canActivate: [AuthGuard]
      }
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PlanRoutingModule { }
