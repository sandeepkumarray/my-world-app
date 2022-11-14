import { NgModule } from '@angular/core';
import { BrowserModule, Title } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ReactiveFormsModule } from '@angular/forms';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { FormsModule } from '@angular/forms';

import { HttpClientModule } from '@angular/common/http';
import {
  PERFECT_SCROLLBAR_CONFIG,
  PerfectScrollbarConfigInterface,
  PerfectScrollbarModule,
} from 'ngx-perfect-scrollbar';

import { IconModule, IconSetService } from '@coreui/icons-angular';

import {
  AvatarModule,
  BadgeModule,
  BreadcrumbModule,
  ButtonGroupModule,
  ButtonModule,
  CardModule,
  DropdownModule,
  FooterModule,
  FormModule,
  GridModule,
  HeaderModule,
  ListGroupModule,
  NavModule,
  NavbarModule,
  ProgressModule,
  SharedModule,
  SidebarModule,
  TabsModule,
  UtilitiesModule,
  CarouselModule
} from '@coreui/angular';

import {
  AppFooterComponent,
  AppHeaderComponent,
  LayoutComponent,
} from './containers';
import { SidebarNavComponent } from './containers/layout/sidebar-nav/sidebar-nav.component';
import { MyworldService } from './service/myworld.service';
import { ContentService } from './service/content.service';
import { Page404Component } from './views/pages/page404/page404.component';
import { Page500Component } from './views/pages/page500/page500.component';
import { LoginComponent } from './views/pages/login/login.component';
import { RegisterComponent } from './views/pages/register/register.component';
import { AuthenticationService } from './service/authentication.service';
import { AppdataService } from './service/appdata.service';
import { AuthGuard } from './utility/AuthGuard';
import { QuillModule } from 'ngx-quill';
import Counter from './utility/Counter';
import { ContentPlanService } from './service/content-plan.service';

const DEFAULT_PERFECT_SCROLLBAR_CONFIG: PerfectScrollbarConfigInterface = {
  suppressScrollX: true,
};

const APP_CONTAINERS = [ 
  AppFooterComponent,
  AppHeaderComponent,
  LayoutComponent,
];
@NgModule({
  declarations: [
    AppComponent,
    APP_CONTAINERS,
    SidebarNavComponent,
    Page404Component,
    Page500Component,
    LoginComponent,
    RegisterComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    AvatarModule,
    HttpClientModule,
    BreadcrumbModule,
    FooterModule,
    DropdownModule,
    GridModule,
    HeaderModule,
    SidebarModule,
    IconModule,
    PerfectScrollbarModule,
    NavModule,
    NavbarModule,
    ButtonModule,
    FormModule,
    UtilitiesModule,
    ButtonGroupModule,
    ReactiveFormsModule,
    SidebarModule,
    SharedModule,
    TabsModule,
    CarouselModule,
    ListGroupModule,
    ProgressModule,
    BadgeModule,
    ListGroupModule,
    CardModule,
    FormsModule,
    QuillModule.forRoot({
      customModules: [{
        implementation: Counter,
        path: 'modules/counter'
      }],
      customOptions: [{
        import: 'formats/font',
        whitelist: ['mirza', 'roboto', 'aref', 'serif', 'sansserif', 'monospace']
      }]
    }),
  ],
  providers: [
    {
      provide: PERFECT_SCROLLBAR_CONFIG,
      useValue: DEFAULT_PERFECT_SCROLLBAR_CONFIG,
    },
    IconSetService,
    Title,
    MyworldService,
    ContentService,
    AppdataService,
    AuthenticationService,
    AuthGuard,
    ContentPlanService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
