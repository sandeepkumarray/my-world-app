import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { FoodsComponent } from './foods.component';
import { ViewFoodsComponent } from './view-foods/view-foods.component';
import { EditFoodsComponent } from './edit-foods/edit-foods.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'foods',
    },
    children: [
      {
        path: '',
        component: FoodsComponent,
        data: {
          title: 'foods',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewFoodsComponent,
        data: {
          title: 'View Food',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditFoodsComponent,
        data: {
          title: 'Edit Food',
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
export class FoodsRoutingModule {

}
