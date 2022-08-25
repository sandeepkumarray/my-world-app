import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { LoresComponent } from './lores.component';
import { ViewLoresComponent } from './view-lores/view-lores.component';
import { EditLoresComponent } from './edit-lores/edit-lores.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'lores',
    },
    children: [
      {
        path: '',
        component: LoresComponent,
        data: {
          title: 'lores',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewLoresComponent,
        data: {
          title: 'View Lore',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditLoresComponent,
        data: {
          title: 'Edit Lore',
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
export class LoresRoutingModule {

}
