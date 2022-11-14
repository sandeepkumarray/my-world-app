import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProfileComponent } from './profile/profile.component';
import { AdminSettingsComponent } from './admin-settings/admin-settings.component';
import { UsersRoutingModule } from './users-routing.module';

import { ReactiveFormsModule } from '@angular/forms';
import { FormsModule } from '@angular/forms';
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
import { ComponentsModule } from 'src/app/components/components.module';
import { UsersComponent } from './users.component';
import { ContentSettingsComponent } from './content-settings/content-settings.component';
import { ContentCustomizationComponent } from './content-settings/content-customization/content-customization.component';


@NgModule({
  declarations: [
    UsersComponent,
    ProfileComponent,
    AdminSettingsComponent,
    ContentSettingsComponent,
    ContentCustomizationComponent
  ],
  imports: [
    CommonModule,
    UsersRoutingModule,
    ReactiveFormsModule,
    FormsModule,
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
    ComponentsModule
  ]
})
export class UsersModule { }
