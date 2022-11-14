import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PlanRoutingModule } from './plan-routing.module';
import { PlanComponent } from './plan.component';

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
  HeaderModule,
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
  ModalModule,
  UtilitiesModule,
  AlertModule
} from '@coreui/angular';


@NgModule({
  declarations: [PlanComponent],
  imports: [
    CommonModule,
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
    HeaderModule,
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
    ModalModule,
    UtilitiesModule,
    AlertModule,
    PlanRoutingModule
  ]
})
export class PlanModule { }
