import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { LayoutComponent } from './containers';
import { Page404Component } from './views/pages/page404/page404.component';
import { Page500Component } from './views/pages/page500/page500.component';
import { LoginComponent } from './views/pages/login/login.component';
import { RegisterComponent } from './views/pages/register/register.component';
import { AuthGuard } from './utility/AuthGuard';
import { FoldersComponent } from './views/documents/folders/folders.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'dashboard',
    pathMatch: 'full'
  },
  {
    path: '',
    component: LayoutComponent,
    data: {
      title: 'Home'
    },
    children: [
      {
        path: 'dashboard',
        loadChildren: () =>
          import('./views/dashboard/dashboard.module').then((m) => m.DashboardModule), canActivate: [AuthGuard]
      },
      {
        path: 'create',
        loadChildren: () =>
          import('./views/dashboard/dashboard.module').then((m) => m.DashboardModule), canActivate: [AuthGuard]
      }, 
      {
        path: 'users',
        loadChildren: () =>
          import('./views/users/users.module').then((m) => m.UsersModule), canActivate: [AuthGuard]
      }, 
      {
        path: 'data',
        loadChildren: () =>
          import('./views/data/data.module').then((m) => m.DataModule), canActivate: [AuthGuard]
      },
      {
        path: 'plan',
        loadChildren: () =>
          import('./views/plan/plan.module').then((m) => m.PlanModule), canActivate: [AuthGuard]
      }, 
      {
        path: 'documents',
        loadChildren: () =>
          import('./views/documents/documents.module').then((m) => m.DocumentsModule), canActivate: [AuthGuard]
      },
      {
        path: 'folders',
        data: {
          title: 'Folders'
        },
        children:[
          {
            path: ':id',
            component: FoldersComponent,
            data: {
              title: 'Folders',
            },
            canActivate: [AuthGuard]
          },
        ]
      },
      {
        path: 'content',
        loadChildren: () => import('./views/content/content.module').then((m) => m.ContentModule),
        data: {
          title: 'content',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'universe',
        loadChildren: () => import('./views/universe/universe.module').then((m) => m.UniverseModule),
        data: {
          title: 'universe',
        },
        canActivate: [AuthGuard]
      }
    ]
  },
  
  {
    path: '404',
    component: Page404Component,
    data: {
      title: 'Page 404'
    }
  },
  {
    path: '500',
    component: Page500Component,
    data: {
      title: 'Page 500'
    }
  },
  {
    path: 'login',
    component: LoginComponent,
    data: {
      title: 'Login Page'
    }
  },
  {
    path: 'register',
    component: RegisterComponent,
    data: {
      title: 'Register Page'
    }
  },
  { path: '**', redirectTo: '404' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {
    useHash: true,
    anchorScrolling: 'enabled',
    onSameUrlNavigation: "reload",
    initialNavigation: 'enabledBlocking',
    scrollPositionRestoration: "enabled"
    // relativeLinkResolution: 'legacy'
  })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
