import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Users } from 'src/app/model';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  form: any = {
    username: null,
    email: null,
    password: null,
    confirmpassword: null
  };
  errorMessage = '';
  constructor(private authService: AuthenticationService,
    private appdataService: AppdataService,
    private router: Router) { }

  ngOnInit(): void {
  }

  onRegister() {
    const { username, email, password, confirmpassword } = this.form;

    if (password == confirmpassword) {

      let encPassword = utility.encrypt(password);
      let user: Users = new Users();
      user.username = username;
      user.encrypted_password = encPassword;
      user.email = email;

      this.appdataService.signupUser(user).subscribe({
        next: response => {
          if (response.success) {
            this.router.navigate(["login"]);
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

      this.errorMessage = "Password doesn't match.";
    }

  }
}
