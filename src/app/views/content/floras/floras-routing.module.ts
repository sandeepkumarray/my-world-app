import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { FlorasComponent } from './floras.component';
import { ViewFlorasComponent } from './view-floras/view-floras.component';
import { EditFlorasComponent } from './edit-floras/edit-floras.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'floras',
    },
    children: [
      {
        path: '',
        component: FlorasComponent,
        data: {
          title: 'floras',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewFlorasComponent,
        data: {
          title: 'View Flora',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditFlorasComponent,
        data: {
          title: 'Edit Flora',
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
export class FlorasRoutingModule {

}
