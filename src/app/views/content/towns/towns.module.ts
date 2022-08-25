import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { IconModule } from '@coreui/icons-angular';
import { TownsComponent } from './towns.component';
import { ViewTownsComponent } from './view-towns/view-towns.component';
import { EditTownsComponent } from './edit-towns/edit-towns.component';
import { TownsRoutingModule } from './towns-routing.module';

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
    TownsComponent,
    ViewTownsComponent,
    EditTownsComponent,
  ],
  imports: [
    CommonModule,
    TownsRoutingModule,AccordionModule,
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
export class TownsModule {

}
