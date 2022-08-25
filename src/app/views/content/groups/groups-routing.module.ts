import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { GroupsComponent } from './groups.component';
import { ViewGroupsComponent } from './view-groups/view-groups.component';
import { EditGroupsComponent } from './edit-groups/edit-groups.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'groups',
    },
    children: [
      {
        path: '',
        component: GroupsComponent,
        data: {
          title: 'groups',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewGroupsComponent,
        data: {
          title: 'View Group',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditGroupsComponent,
        data: {
          title: 'Edit Group',
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
export class GroupsRoutingModule {

}
