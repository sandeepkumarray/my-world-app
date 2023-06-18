import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UniverseRoutingModule } from './universe-routing.module';
import { UniverseComponent } from './universe.component';
import { IconModule } from '@coreui/icons-angular';
import { ReactiveFormsModule } from '@angular/forms';
import { ButtonModule, CardModule, DropdownModule, FormModule, GridModule, SharedModule, WidgetModule, ProgressModule } from '@coreui/angular';

@NgModule({
  declarations: [
    UniverseComponent
  ],
  imports: [
    CommonModule,
    UniverseRoutingModule,
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
    ProgressModule
  ]
})
export class UniverseModule { }
