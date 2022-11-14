import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { AdminSettingsComponent } from './admin-settings/admin-settings.component';
import { ContentCustomizationComponent } from './content-settings/content-customization/content-customization.component';
import { ContentSettingsComponent } from './content-settings/content-settings.component';
import { ProfileComponent } from './profile/profile.component';
import { UsersComponent } from './users.component';

const routes: Routes = [ {
  path: '',
  data: {
    title: '',
  },
  children: [
    {
      path: '',
      component: UsersComponent,
      data: {
        title: 'Data',
      },
      canActivate: [AuthGuard]
    },
    {
      path: 'profile',
      component: ProfileComponent,
      data: {
        title: 'Data Import',
      },
      canActivate: [AuthGuard]
    },
    {
      path: 'settings',
      component: AdminSettingsComponent,
      data: {
        title: 'Data Export',
      },
      canActivate: [AuthGuard]
    },
    {
      path:'content/settings',
      component: ContentSettingsComponent, 
      data: {
        title: 'Content Settings',
      },
      canActivate: [AuthGuard]
    },
    {
      path:'content/settings/:content_type',
      component: ContentCustomizationComponent, 
      data: {
        title: 'Content Settings',
      },
      canActivate: [AuthGuard]
    }
  ]
},
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UsersRoutingModule { }
