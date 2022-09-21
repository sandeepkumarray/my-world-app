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
        path: 'data',
        loadChildren: () =>
          import('./views/data/data.module').then((m) => m.DataModule), canActivate: [AuthGuard]
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
        path: 'buildings',
        loadChildren: () => import('./views/content/buildings/buildings.module').then((m) => m.BuildingsModule),
        data: {
          title: 'Buildings',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'characters',
        loadChildren: () => import('./views/content/characters/characters.module').then((m) => m.CharactersModule),
        data: {
          title: 'Characters',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'conditions',
        loadChildren: () => import('./views/content/conditions/conditions.module').then((m) => m.ConditionsModule),
        data: {
          title: 'Conditions',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'continents',
        loadChildren: () => import('./views/content/continents/continents.module').then((m) => m.ContinentsModule),
        data: {
          title: 'Continents',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'countries',
        loadChildren: () => import('./views/content/countries/countries.module').then((m) => m.CountriesModule),
        data: {
          title: 'Countries',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'creatures',
        loadChildren: () => import('./views/content/creatures/creatures.module').then((m) => m.CreaturesModule),
        data: {
          title: 'Creatures',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'deities',
        loadChildren: () => import('./views/content/deities/deities.module').then((m) => m.DeitiesModule),
        data: {
          title: 'Deities',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'floras',
        loadChildren: () => import('./views/content/floras/floras.module').then((m) => m.FlorasModule),
        data: {
          title: 'Floras',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'foods',
        loadChildren: () => import('./views/content/foods/foods.module').then((m) => m.FoodsModule),
        data: {
          title: 'Foods',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'governments',
        loadChildren: () => import('./views/content/governments/governments.module').then((m) => m.GovernmentsModule),
        data: {
          title: 'Governments',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'groups',
        loadChildren: () => import('./views/content/groups/groups.module').then((m) => m.GroupsModule),
        data: {
          title: 'Groups',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'items',
        loadChildren: () => import('./views/content/items/items.module').then((m) => m.ItemsModule),
        data: {
          title: 'Items',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'jobs',
        loadChildren: () => import('./views/content/jobs/jobs.module').then((m) => m.JobsModule),
        data: {
          title: 'Jobs',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'landmarks',
        loadChildren: () => import('./views/content/landmarks/landmarks.module').then((m) => m.LandmarksModule),
        data: {
          title: 'Landmarks',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'languages',
        loadChildren: () => import('./views/content/languages/languages.module').then((m) => m.LanguagesModule),
        data: {
          title: 'Languages',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'locations',
        loadChildren: () => import('./views/content/locations/locations.module').then((m) => m.LocationsModule),
        data: {
          title: 'Locations',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'lores',
        loadChildren: () => import('./views/content/lores/lores.module').then((m) => m.LoresModule),
        data: {
          title: 'Lores',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'magics',
        loadChildren: () => import('./views/content/magics/magics.module').then((m) => m.MagicsModule),
        data: {
          title: 'Magics',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'organizations',
        loadChildren: () => import('./views/content/organizations/organizations.module').then((m) => m.OrganizationsModule),
        data: {
          title: 'Organizations',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'planets',
        loadChildren: () => import('./views/content/planets/planets.module').then((m) => m.PlanetsModule),
        data: {
          title: 'Planets',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'races',
        loadChildren: () => import('./views/content/races/races.module').then((m) => m.RacesModule),
        data: {
          title: 'Races',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'religions',
        loadChildren: () => import('./views/content/religions/religions.module').then((m) => m.ReligionsModule),
        data: {
          title: 'Religions',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'scenes',
        loadChildren: () => import('./views/content/scenes/scenes.module').then((m) => m.ScenesModule),
        data: {
          title: 'Scenes',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'sports',
        loadChildren: () => import('./views/content/sports/sports.module').then((m) => m.SportsModule),
        data: {
          title: 'Sports',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'technologies',
        loadChildren: () => import('./views/content/technologies/technologies.module').then((m) => m.TechnologiesModule),
        data: {
          title: 'Technologies',
        },
        canActivate: [AuthGuard]
      },
      // {
      //   path: 'timelines',
      //   loadChildren: () => import('./views/content/timelines/timelines.module').then((m) => m.TimelinesModule),
      //   data: {
      //     title: 'Timelines',
      //   },
      //   canActivate: [AuthGuard]
      // },
      {
        path: 'towns',
        loadChildren: () => import('./views/content/towns/towns.module').then((m) => m.TownsModule),
        data: {
          title: 'Towns',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'traditions',
        loadChildren: () => import('./views/content/traditions/traditions.module').then((m) => m.TraditionsModule),
        data: {
          title: 'Traditions',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'universes',
        loadChildren: () => import('./views/content/universes/universes.module').then((m) => m.UniversesModule),
        data: {
          title: 'Universes',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'vehicles',
        loadChildren: () => import('./views/content/vehicles/vehicles.module').then((m) => m.VehiclesModule),
        data: {
          title: 'Vehicles',
        },
        canActivate: [AuthGuard]
      },
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
