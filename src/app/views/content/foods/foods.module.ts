import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { IconModule } from '@coreui/icons-angular';
import { FoodsComponent } from './foods.component';
import { ViewFoodsComponent } from './view-foods/view-foods.component';
import { EditFoodsComponent } from './edit-foods/edit-foods.component';
import { FoodsRoutingModule } from './foods-routing.module';

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
    FoodsComponent,
    ViewFoodsComponent,
    EditFoodsComponent,
  ],
  imports: [
    CommonModule,
    FoodsRoutingModule,AccordionModule,
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
export class FoodsModule {

}
