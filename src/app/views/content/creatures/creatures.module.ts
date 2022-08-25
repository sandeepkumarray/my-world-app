import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { IconModule } from '@coreui/icons-angular';
import { CreaturesComponent } from './creatures.component';
import { ViewCreaturesComponent } from './view-creatures/view-creatures.component';
import { EditCreaturesComponent } from './edit-creatures/edit-creatures.component';
import { CreaturesRoutingModule } from './creatures-routing.module';

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
  UtilitiesModule
} from '@coreui/angular';
import { QuillModule } from 'ngx-quill';

@NgModule({
  declarations: [
    CreaturesComponent,
    ViewCreaturesComponent,
    EditCreaturesComponent,
  ],
  imports: [
    CommonModule,
    CreaturesRoutingModule,AccordionModule,
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
    QuillModule.forRoot()
  ]
})
export class CreaturesModule {

}
