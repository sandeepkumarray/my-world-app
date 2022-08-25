import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { ScenesComponent } from './scenes.component';
import { ViewScenesComponent } from './view-scenes/view-scenes.component';
import { EditScenesComponent } from './edit-scenes/edit-scenes.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'scenes',
    },
    children: [
      {
        path: '',
        component: ScenesComponent,
        data: {
          title: 'scenes',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewScenesComponent,
        data: {
          title: 'View Scene',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditScenesComponent,
        data: {
          title: 'Edit Scene',
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
export class ScenesRoutingModule {

}
