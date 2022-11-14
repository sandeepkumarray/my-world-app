import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { IconModule } from '@coreui/icons-angular';
import { TimelinesComponent } from './timelines.component';
import { ViewTimelinesComponent } from './view-timelines/view-timelines.component';
import { EditTimelinesComponent } from './edit-timelines/edit-timelines.component';
import { TimelinesRoutingModule } from './timelines-routing.module';

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

@NgModule({
  declarations: [
    TimelinesComponent,
    ViewTimelinesComponent,
    EditTimelinesComponent,
  ],
  imports: [
    CommonModule,
    TimelinesRoutingModule,AccordionModule,
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
    QuillModule.forRoot()
  ]
})
export class TimelinesModule {

}
