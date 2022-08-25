import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, RouterStateSnapshot, CanActivate, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthenticationService } from '../service/authentication.service';
@Injectable({
    providedIn: 'root'
})
export class AuthGuard implements CanActivate {
    constructor(private router: Router, private authenticationService: AuthenticationService) { }
    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
        if (this.isLoggedIn()) {
            return true;
        }
        // navigate to login page as user is not authenticated      
        this.router.navigate(['/login']);
        return false;
    }
    public isLoggedIn(): boolean {
        let status = false;
        if (this.authenticationService.getUser() == null) {
            status = false;
        }
        else {
            status = true;
        }
        return status;
    }
}    