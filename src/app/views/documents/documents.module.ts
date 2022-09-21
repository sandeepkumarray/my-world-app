import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IconModule } from '@coreui/icons-angular';
import { ReactiveFormsModule } from '@angular/forms';
import { DocumentsRoutingModule } from './documents-routing.module';
import { DocumentsComponent } from './documents.component';
import { ViewDocumentsComponent } from './view-documents/view-documents.component';
import { EditDocumentsComponent } from './edit-documents/edit-documents.component';
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
  UtilitiesModule
} from '@coreui/angular';
import { QuillModule } from 'ngx-quill';
import { FoldersComponent } from './folders/folders.component';
import { GridDocumentsComponent } from './grid-documents/grid-documents.component';


@NgModule({
  declarations: [DocumentsComponent, ViewDocumentsComponent, EditDocumentsComponent, FoldersComponent, GridDocumentsComponent],
  imports: [
    CommonModule,
    FormModule,
    ReactiveFormsModule,
    DocumentsRoutingModule,
    AccordionModule,
    BadgeModule,
    BreadcrumbModule,
    ButtonModule,
    CardModule,
    CollapseModule,
    GridModule,
    HeaderModule,
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
    TableModule,ModalModule,FormsModule,
    QuillModule.forRoot()
  ]
})
export class DocumentsModule { }
