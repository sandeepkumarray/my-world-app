import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, ValidationErrors, Validators } from '@angular/forms';
import { DomSanitizer } from '@angular/platform-browser';
import { Router } from '@angular/router';
import { ContentPlans, ContentTypes, UserPlan, Users } from 'src/app/model';
import { BaseModel } from 'src/app/model/BaseModel';
import { UserBlob } from 'src/app/model/UserBlob';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentPlanService } from 'src/app/service/content-plan.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { FileUploadModel } from 'src/app/usermodels/FileUploadModel';
import { constants } from 'src/app/utility/constants';
import { utility } from 'src/app/utility/utility';

/** passwords must match - custom validator */
export function confirmPasswordValidator(control: FormGroup): ValidationErrors | null {
  const password = control.get('password');
  const confirm = control.get('confirmPassword');
  return password?.value && password?.value === confirm?.value
    ? null
    : { passwordMismatch: true };
};


@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
  user: Users = new Users();
  profilePhoto: any = "./assets/img/avatars/6.jpg";
  Constants = constants;

  passwordForm!: FormGroup;
  submitted = false;
  formErrors: any;

  contentPlans: ContentPlans[] = [];
  current_plan: ContentPlans = new ContentPlans();

  constructor(private fb: FormBuilder, private appdataService: AppdataService,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private sanitized: DomSanitizer,
    private contentService: ContentService,
    private contentPlanService: ContentPlanService,
    private router: Router) { }

  ngOnInit(): void {

    this.contentPlanService.get_User_Plans().subscribe({
      next: (res: any) => {
        if (res != null) {
          this.current_plan = res;

          this.myworldService.getAllContentPlans().subscribe({
            next: response => {
              //this.contentPlans = response;
              response.map(
                (c) => {
                  if (this.current_plan.id <= c.id) {

                    c.plan_contents = [];
                    var plan_desc = c.plan_description.split(',');
                    if (this.current_plan.id == c.id) {
                      c.is_currentPlan = true;
                    } else {
                      c.is_currentPlan = false;
                    }
                    plan_desc.forEach(d => {
                      var content_desc = d.split(' ');
                      var content_type = content_desc[1];
                      var count = content_desc[0];
                      var content_type_details = this.authService.getValue(content_type) as ContentTypes;
                      content_type_details.count = Number(count);
                      content_type_details.fa_icon = content_type_details.fa_icon!.replace("fa-3x", "") + " " + content_type_details.name.toLowerCase() + "-pri";

                      c.plan_contents.push(content_type_details);
                    });
                    this.contentPlans.push(c);
                  }
                }
              );
            }
          });
        }
      }
    });

    let accountId = (this.authService.getUser() as (Users)).id;
    this.myworldService.getUserBlob(accountId, 'ProfilePhoto').subscribe({
      next: response => {
        if (response != null)
          this.profilePhoto = this.sanitized.bypassSecurityTrustResourceUrl('data:' + response.file_type + ';base64,' + response.blob);
      }
    });

    this.myworldService.getUsers(accountId).subscribe({
      next: response => {
        this.user = response;
      }
    });

    this.formErrors = {
      password: {
        required: 'Password is required',
        pattern: 'Password must contain: numbers, uppercase and lowercase letters',
        minLength: `Password must be at least 6 characters`
      },
      confirmPassword: {
        required: 'Password confirmation is required',
        passwordMismatch: 'Passwords must match'
      }
    };

    this.passwordForm = this.fb.group(
      {
        password: [
          '',
          [
            Validators.required,
            Validators.minLength(6),
            Validators.pattern('(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{6,}'),
          ],
        ],
        confirmPassword: ['', [Validators.required]]
      },
      { validators: confirmPasswordValidator }
    );

  }

  // convenience getter for easy access to form fields
  get f() {
    return this.passwordForm.controls;
  }

  onValidate() {
    this.submitted = true;

    // stop here if form is invalid
    return this.passwordForm.status === 'VALID';
  }

  onSubmit() {
    console.warn(this.onValidate(), this.passwordForm.value);

    if (this.onValidate()) {
      // TODO: Submit form value
      this.changePassword();
    }
  }

  checkValue($event: any, field_name: string) {
    let accountId = (this.authService.getUser() as (Users)).id;
    let value = Number($event.target.checked);

    let model: BaseModel = new BaseModel();
    model._id = Number(accountId);
    model.column_type = field_name;
    model.column_value = value;
    model.content_type = "users";
    this.contentService.saveData(model).subscribe({
      next: response => {
      }
    });
  }

  onBlur($event: any, field_name: string) {

    let accountId = (this.authService.getUser() as (Users)).id;
    let model: BaseModel = new BaseModel();
    model._id = Number(accountId);
    model.column_type = field_name;
    model.column_value = $event.target.value;
    model.content_type = "users";
    this.contentService.saveData(model).subscribe({
      next: response => {
      }
    });
  }

  addImage(event: any) {
    let accountId = (this.authService.getUser() as (Users)).id;
    if (event.target.files && event.target.files[0]) {
      this.profilePhoto = "";
      let file: any = event.target.files[0];

      const reader = new FileReader();

      reader.onload = (e) => {
        this.profilePhoto = reader.result;
      }
      reader.readAsDataURL(file);

      let aFile: FileUploadModel = new FileUploadModel();
      aFile.blob = file;
      aFile.inProgress = false;
      aFile.progress = 0;
      aFile.file_type = file.type;
      aFile.name = file.name;
      aFile.size = file.size;
      aFile.user_id = accountId;
      aFile.type = "ProfilePhoto";

      this.myworldService.blobUpload(aFile).subscribe();
    }
  }

  changePassword() {

    let accountId = (this.authService.getUser() as (Users)).id;
    if (this.passwordForm.valid) {
      let password = this.passwordForm.controls['password'].value;
      let confirmPassword = this.passwordForm.controls['confirmPassword'].value;
      let encPassword = utility.encrypt(confirmPassword);

      let model: BaseModel = new BaseModel();
      model._id = Number(accountId);
      model.column_type = "encrypted_password";
      model.column_value = encPassword;
      model.content_type = "users";
      this.contentService.saveData(model).subscribe({
        next: response => {
        }
      });
    }
  }

  changePlan(plan_id: any) {
    let userplan: UserPlan = new UserPlan();

    this.myworldService.updateUserPlan(userplan);
  }
}