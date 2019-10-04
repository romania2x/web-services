import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs';

@Injectable()
export class SecurityService {
    private user: BehaviorSubject<any> = new BehaviorSubject(null);

    getUser(): BehaviorSubject<any> {
        return this.user;
    }

    login() {
        this.user.next({firstName: 'Sorin', lastName: 'Badea', email: 'sorin.badea91@gmail.com'});
    }
}
