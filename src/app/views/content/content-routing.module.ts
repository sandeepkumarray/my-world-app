import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { ContentComponent } from './content.component';
import { EditComponent } from './edit/edit.component';
import { ViewComponent } from './view/view.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'content',
    },
    children: [
      {
        path: ':content_type',
        component: ContentComponent,
        data: {
          title: 'contents',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':content_type/:id',
        component: ViewComponent,
        data: {
          title: 'View content',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':content_type/:id/edit',
        component: EditComponent,
        data: {
          title: 'Edit content',
        },
        canActivate: [AuthGuard]
      },
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ContentRoutingModule { }
