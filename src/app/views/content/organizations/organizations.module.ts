import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { IconModule } from '@coreui/icons-angular';
import { OrganizationsComponent } from './organizations.component';
import { ViewOrganizationsComponent } from './view-organizations/view-organizations.component';
import { EditOrganizationsComponent } from './edit-organizations/edit-organizations.component';
import { OrganizationsRoutingModule } from './organizations-routing.module';

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
    OrganizationsComponent,
    ViewOrganizationsComponent,
    EditOrganizationsComponent,
  ],
  imports: [
    CommonModule,
    OrganizationsRoutingModule,AccordionModule,
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
export class OrganizationsModule {

}
