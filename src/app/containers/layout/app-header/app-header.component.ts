import { Component, Input, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { Router } from '@angular/router';
import { ClassToggleService, HeaderComponent } from '@coreui/angular';
import { Users } from 'src/app/model';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { MyworldService } from 'src/app/service/myworld.service';

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

  profilePhoto: any = "./assets/img/avatars/6.jpg";
  
  constructor(private classToggler: ClassToggleService,
    private myworldService: MyworldService,
    private authService: AuthenticationService,
    private sanitized: DomSanitizer,
    private router: Router) {
    super();
  }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.myworldService.getUserBlob(accountId, 'ProfilePhoto').subscribe({
      next: response => {
        this.profilePhoto = this.sanitized.bypassSecurityTrustResourceUrl('data:' + response.file_type + ';base64,' + response.blob);
      }
    });

  }

  onLogout(){
    this.authService.logout();
    this.router.navigate(["login"]);
  }
}
