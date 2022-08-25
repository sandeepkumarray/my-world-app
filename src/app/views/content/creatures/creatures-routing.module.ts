import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { CreaturesComponent } from './creatures.component';
import { ViewCreaturesComponent } from './view-creatures/view-creatures.component';
import { EditCreaturesComponent } from './edit-creatures/edit-creatures.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'creatures',
    },
    children: [
      {
        path: '',
        component: CreaturesComponent,
        data: {
          title: 'creatures',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewCreaturesComponent,
        data: {
          title: 'View Creature',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditCreaturesComponent,
        data: {
          title: 'Edit Creature',
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
export class CreaturesRoutingModule {

}
