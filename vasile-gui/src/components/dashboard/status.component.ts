import {Component, NgZone, OnInit} from '@angular/core';
import {SystemProvider} from '../../providers/system.provider';


@Component({
  moduleId: 'dashboard-status',
  template: `
      <ul class="list-group">
          <li *ngFor="let test of tests" class="list-group-item bg-dark text-sm-left"
              [ngClass]="{'text-danger':!test.state,'text-success':test.state}"
              title="{{test.error|json}}">{{test.name}}</li>
      </ul>
  `
})
export class DashboardStatusComponent implements OnInit {
  public tests: any[];

  public constructor(private systemProvider: SystemProvider, private ngZone: NgZone) {
  }

  ngOnInit(): void {
    this.sync();
    // const url: any = new URL('http://public.vasile/mercure/hub');
    // url.searchParams.append('topic', 'test');
    //
    // const eventSource = new EventSource(url);
    //
    // eventSource.onmessage = e => console.log(e);

  }

  public sync() {

    this.systemProvider.fetchAPIHealthcheck().subscribe(
      (result) => {
        this.ngZone.run(() => {
          this.tests = result;
        });
        setTimeout(() => this.sync(), 5000);
      },
      (error) => {
        this.ngZone.run(() => {
          this.tests = error.error;
        });
        setTimeout(() => this.sync(), 5000);
      }
    );
  }

}
