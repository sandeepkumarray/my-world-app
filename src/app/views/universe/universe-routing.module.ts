import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { UniverseComponent } from './universe.component';


const routes: Routes = [
  {
    path: '',
    data: {
      title: 'universe',
    },
    children: [
      {
        path: ':id',
        component: UniverseComponent,
        data: {
          title: 'universe',
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
export class UniverseRoutingModule { }
