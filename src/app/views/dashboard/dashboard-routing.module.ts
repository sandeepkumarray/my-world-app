import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DashboardComponent } from './dashboard.component';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { RouterModule, Routes } from '@angular/router';
import { CreateContentComponent } from './create-content/create-content.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'dashboard',
    },
    children: [
      {
        path: '',
        component: DashboardComponent,
        data: {
          title: 'dashboard',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':content_type',
        component: CreateContentComponent,
        data: {
          title: 'Create Item',
        },
        canActivate: [AuthGuard]
      },
    ]
  },
];


@NgModule({
  declarations: [], 
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DashboardRoutingModule { }
