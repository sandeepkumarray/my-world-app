import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { ItemsComponent } from './items.component';
import { ViewItemsComponent } from './view-items/view-items.component';
import { EditItemsComponent } from './edit-items/edit-items.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'items',
    },
    children: [
      {
        path: '',
        component: ItemsComponent,
        data: {
          title: 'items',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewItemsComponent,
        data: {
          title: 'View Item',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditItemsComponent,
        data: {
          title: 'Edit Item',
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
export class ItemsRoutingModule {

}
