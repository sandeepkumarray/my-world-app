import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { ConditionsComponent } from './conditions.component';
import { ViewConditionsComponent } from './view-conditions/view-conditions.component';
import { EditConditionsComponent } from './edit-conditions/edit-conditions.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'conditions',
    },
    children: [
      {
        path: '',
        component: ConditionsComponent,
        data: {
          title: 'conditions',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewConditionsComponent,
        data: {
          title: 'View Condition',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditConditionsComponent,
        data: {
          title: 'Edit Condition',
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
export class ConditionsRoutingModule {

}
