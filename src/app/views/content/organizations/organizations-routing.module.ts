import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { OrganizationsComponent } from './organizations.component';
import { ViewOrganizationsComponent } from './view-organizations/view-organizations.component';
import { EditOrganizationsComponent } from './edit-organizations/edit-organizations.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'organizations',
    },
    children: [
      {
        path: '',
        component: OrganizationsComponent,
        data: {
          title: 'organizations',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewOrganizationsComponent,
        data: {
          title: 'View Organization',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditOrganizationsComponent,
        data: {
          title: 'Edit Organization',
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
export class OrganizationsRoutingModule {

}
