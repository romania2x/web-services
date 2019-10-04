import {Component, OnInit} from '@angular/core';
import {SecurityService} from '../services/security.service';
import {Router} from '@angular/router';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
    constructor(private securityService: SecurityService, private router: Router) {
    }

    ngOnInit(): void {
        this.securityService.getUser().subscribe(user => {
            // if (user == null) {
            //     if (this.router.url == '/login') {
            //         return;
            //     }
            //     this.router.navigateByUrl('/login');
            // } else {
            //     this.router.navigateByUrl('/');
            // }
        });
    }
}
