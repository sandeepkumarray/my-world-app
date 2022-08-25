import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { TraditionsComponent } from './traditions.component';
import { ViewTraditionsComponent } from './view-traditions/view-traditions.component';
import { EditTraditionsComponent } from './edit-traditions/edit-traditions.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'traditions',
    },
    children: [
      {
        path: '',
        component: TraditionsComponent,
        data: {
          title: 'traditions',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewTraditionsComponent,
        data: {
          title: 'View Tradition',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditTraditionsComponent,
        data: {
          title: 'Edit Tradition',
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
export class TraditionsRoutingModule {

}
