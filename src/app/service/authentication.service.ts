import { Injectable, Optional, SkipSelf } from '@angular/core';
import { Users } from '../model';
import { utility } from '../utility/utility';

const TOKEN_KEY = 'auth-token';
const USER_KEY = 'auth-user';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {
  private user!: Users;

  constructor(@Optional() @SkipSelf() sharedservice?: AuthenticationService) {
    if (sharedservice) {
      throw new Error(
        'AuthenticationService is already loaded');
    }
    console.info('Auth Service created');
  }
  signOut(): void {
    window.localStorage.clear();
  }

  public saveToken(token: string): void {
    window.localStorage.removeItem(TOKEN_KEY);
    window.localStorage.setItem(TOKEN_KEY, token);
  }

  public getToken(): string | null {
    return window.localStorage.getItem(TOKEN_KEY);
  }

  public setUser(user: any): void {
    window.localStorage.removeItem(USER_KEY);
    window.localStorage.setItem(USER_KEY, utility.encrypt(JSON.stringify(user)));
  }

  public getUser(): any {
    const user = window.localStorage.getItem(USER_KEY);
    if (user) {
      return JSON.parse(utility.decrypt(user));
    }
    else
      return null;
  }

  public logout(): any {
    window.localStorage.clear();
  }

  public setValue(key: string, value: any) {
    window.localStorage.removeItem(key);
    window.localStorage.setItem(key, utility.encrypt(JSON.stringify(value)));
  }

  public getValue(key: string): any {
    const value = window.localStorage.getItem(key);
    if (value) {
      return JSON.parse(utility.decrypt(value));
    }
    else
      return null;
  }
}