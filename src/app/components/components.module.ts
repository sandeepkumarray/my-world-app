import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ComingSoonComponent } from 'src/app/components/coming-soon/coming-soon.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
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
import { ContentSelectComponent } from './content-select/content-select.component';

@NgModule({
  declarations: [
    ComingSoonComponent,
    ContentSelectComponent
  ],
  exports:[    
    ComingSoonComponent,
    ContentSelectComponent
  ],
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
    FormsModule,
    ReactiveFormsModule
  ]
})
export class ComponentsModule { }
