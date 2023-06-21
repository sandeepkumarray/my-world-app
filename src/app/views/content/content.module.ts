import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { IconModule } from '@coreui/icons-angular';
import { ViewComponent } from './view/view.component';
import { EditComponent } from './edit/edit.component';
import { ContentComponent } from './content.component';
import { ContentRoutingModule } from './content-routing.module';

import {
  AccordionModule,
  BadgeModule,
  BreadcrumbModule,
  ButtonModule,
  CardModule,
  CarouselModule,
  CollapseModule,
  DropdownModule,
  FormModule,
  GridModule,
  ListGroupModule,
  NavModule,
  PaginationModule,
  PlaceholderModule,
  PopoverModule,
  ProgressModule,
  SharedModule,
  SpinnerModule,
  TableModule,
  TabsModule,
  TooltipModule,
  UtilitiesModule,
  ModalModule
} from '@coreui/angular';
import { QuillModule } from 'ngx-quill';
import { ComponentsModule } from 'src/app/components/components.module';
import { ChangelogComponent } from './changelog/changelog.component';

@NgModule({
  declarations: [
    ContentComponent,
    ViewComponent,
    EditComponent,
    ChangelogComponent,
  ],
  imports: [
    CommonModule,
    ContentRoutingModule,
    AccordionModule,
    BadgeModule,
    BreadcrumbModule,
    ButtonModule,
    CardModule,
    CollapseModule,
    GridModule,
    UtilitiesModule,
    SharedModule,
    ListGroupModule,
    IconModule,
    ListGroupModule,
    PlaceholderModule,
    ProgressModule,
    SpinnerModule,
    TabsModule,
    NavModule,
    TooltipModule,
    CarouselModule,
    FormModule,
    ReactiveFormsModule,
    DropdownModule,
    PaginationModule,
    PopoverModule,
    TableModule,
    FormsModule,
    ModalModule,
    QuillModule.forRoot(),
    ComponentsModule
  ]
})
export class ContentModule { }
