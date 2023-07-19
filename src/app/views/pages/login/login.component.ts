import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { constants } from 'src/app/utility/constants';
import { Users } from 'src/app/model';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  customStylesValidated = false;

  public LoginForm!: FormGroup;

  errorMessage = '';

  constructor(private myworldService: MyworldService,
    private authService: AuthenticationService,
    private appdataService: AppdataService,
    private router: Router) {

  }

  ngOnInit(): void {
    this.LoginForm = new FormGroup({
      username: new FormControl('', [Validators.required, Validators.maxLength(120)]),
      password: new FormControl('', [Validators.required, Validators.maxLength(16)])
    });

  }

  public hasError = (controlName: string, errorName: string) => {
    return this.LoginForm.controls[controlName].hasError(errorName);
  }

  onLogin() {
    this.customStylesValidated = true;
    if (this.LoginForm.valid) {
      let username = this.LoginForm.controls['username'].value;
      let password = this.LoginForm.controls['password'].value;

      let encPassword = utility.encrypt(password);
      let decryptstring = utility.decrypt(encPassword);

      this.appdataService.loginUser(username, encPassword).subscribe({
        next: response => {
          if (response.success) {
            this.authService.setUser(response.data);
            let user = response.data as Users;

            console.log("login user", user);
            // this.myworldService.getObjectStorageKeys(1, 1).subscribe({
            //   next: response => {
            //     this.myworldService.getUserContentBucket(user.id).subscribe({
            //       next: res => {
            //         response.bucketName = res.bucket_Name;
            //         this.authService.setValue(constants.ObjectStorageKey, response);
            //       }
            //     });
            //   }
            // });

            this.myworldService.getAllAppConfig(1).subscribe({
              next: (res) => {
                res.map(cnfg => {
                  this.authService.setValue(cnfg.key, cnfg.value);
                });
              }
            });

            this.myworldService.getAllContentTypes().subscribe({
              next: (res) => {
                res.map(cnfg => {
                  this.authService.setValue(cnfg.content_type, cnfg);
                });
              }
            });

            this.router.navigate(["dashboard"]);
          }
          else {
            this.errorMessage = response.message;
          }
        },
        error: err => {
          this.errorMessage = err.error.message;
        }
      });
    }
    else {
      console.log("Form is in-valid");
    }
  }
}
