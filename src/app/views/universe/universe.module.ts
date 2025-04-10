import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UniverseRoutingModule } from './universe-routing.module';
import { UniverseComponent } from './universe.component';
import { IconModule } from '@coreui/icons-angular';
import { ReactiveFormsModule } from '@angular/forms';
import { AccordionModule, ButtonModule, CardModule, DropdownModule, FormModule, GridModule, SharedModule, WidgetModule, ProgressModule } from '@coreui/angular';
import { ComponentsModule } from 'src/app/components/components.module';

@NgModule({
  declarations: [
    UniverseComponent
  ],
  imports: [    
  AccordionModule,
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
    ProgressModule,
    ComponentsModule
  ]
})
export class UniverseModule { }
