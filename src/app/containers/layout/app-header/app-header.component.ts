import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ClassToggleService, HeaderComponent } from '@coreui/angular';
import { AuthenticationService } from 'src/app/service/authentication.service';

@Component({
  selector: 'app-header',
  templateUrl: './app-header.component.html',
  styleUrls: ['./app-header.component.css']
})
export class AppHeaderComponent extends HeaderComponent implements OnInit {

  @Input() sidebarId: string = "sidebar";

  public newMessages = new Array(4)
  public newTasks = new Array(5)
  public newNotifications = new Array(5)

  constructor(private classToggler: ClassToggleService,
    private authService: AuthenticationService,
    private router: Router) {
    super();
  }

  ngOnInit(): void {
  }

  onLogout(){
    this.authService.logout();
    this.router.navigate(["login"]);
  }
}
