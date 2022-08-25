import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { CharactersComponent } from './characters.component';
import { ViewCharactersComponent } from './view-characters/view-characters.component';
import { EditCharactersComponent } from './edit-characters/edit-characters.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'characters',
    },
    children: [
      {
        path: '',
        component: CharactersComponent,
        data: {
          title: 'characters',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewCharactersComponent,
        data: {
          title: 'View Character',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditCharactersComponent,
        data: {
          title: 'Edit Character',
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
export class CharactersRoutingModule {

}
