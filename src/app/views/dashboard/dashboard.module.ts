import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DashboardComponent } from './dashboard.component';
import { DashboardRoutingModule } from './dashboard-routing.module';
import { CreateContentComponent } from './create-content/create-content.component';
import { ButtonModule, CardModule, DropdownModule, FormModule, GridModule, SharedModule, WidgetModule, ProgressModule } from '@coreui/angular';
import { IconModule } from '@coreui/icons-angular';
import { ReactiveFormsModule } from '@angular/forms';



@NgModule({
  declarations: [
    DashboardComponent, 
    CreateContentComponent
  ],
  imports: [
    CommonModule, 
    FormModule,
    ReactiveFormsModule,
    IconModule,
    GridModule,
    WidgetModule,
    GridModule,
    WidgetModule,
    DropdownModule,
    SharedModule,
    ButtonModule,
    CardModule,
    DashboardRoutingModule,
    ProgressModule
  ]
})
export class DashboardModule { }
