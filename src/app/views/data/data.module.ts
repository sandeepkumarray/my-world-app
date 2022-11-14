import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IconModule } from '@coreui/icons-angular';
import { ReactiveFormsModule } from '@angular/forms';
import { ImportComponent } from './import/import.component';
import { FormsModule } from '@angular/forms';
import { DataRoutingModule } from './data-routing.module';
import { DataComponent } from './data.component';
import { ComponentsModule } from 'src/app/components/components.module';

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
import { ExportComponent } from './export/export.component';
import { ImagesManageComponent } from './images-manage/images-manage.component';
import { UsageComponent } from './usage/usage.component';

@NgModule({
  declarations: [ImportComponent, DataComponent, ExportComponent, ImagesManageComponent, UsageComponent],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    DataRoutingModule,
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
    AlertModule,ComponentsModule
  ]
})
export class DataModule { }
