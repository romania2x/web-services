import {Component, NgZone, OnInit} from '@angular/core';
import {MenuItem} from 'primeng/api';
import {SystemProvider} from '../providers/system.provider';

@Component({
  moduleId: 'app-dashboard',
  template: `
      <p-menubar [model]='items'></p-menubar>
      <golden-layout-root></golden-layout-root>`,
})
export class DashboardComponent implements OnInit {
  private static HEALTHCHECK_REFRESH_INTERVAL = 5000;
  healthchecks: any[];
  items: MenuItem[];
  lastState = false;

  constructor(private systemProvider: SystemProvider, private ngZone: NgZone) {
  }

  ngOnInit(): void {
    this.sync();
  }

  public sync() {

    // this.systemProvider.fetchAPIHealthcheck().subscribe(
    //   (result) => {
    //     this.ngZone.run(() => {
    //       this.setHealthchecks(result, true);
    //     });
    //     setTimeout(() => this.sync(), DashboardComponent.HEALTHCHECK_REFRESH_INTERVAL);
    //   },
    //   (error) => {
    //     this.ngZone.run(() => {
    //       this.setHealthchecks(error.error, false);
    //     });
    //     setTimeout(() => this.sync(), DashboardComponent.HEALTHCHECK_REFRESH_INTERVAL);
    //   }
    // );
  }

  private setHealthchecks(healthchecks: any[], state) {
    this.healthchecks = healthchecks.map(test => {
      return {
        label: test.name,
        icon: 'pi ' + (test.state ? 'pi-spin pi-spinner' : 'pi-times')
      };
    });

    if (this.lastState !== state) {
      this.lastState = state;
      this.buildMenuItems();
    }
    this.lastState = state;
  }

  private buildMenuItems() {
    this.items = [
      {
        label: 'Workspace', icon: 'pi pi-sitemap',
        items: [
          {label: 'Projects', icon: 'pi pi-folder-open'},
          {label: 'Devices', icon: 'pi pi-wifi'}
        ]
      },
      {
        label: 'Data sets', icon: 'fas fa-database',
        items: [
          {
            label: 'OpenStreetMap', icon: 'fas fa-globe',
            items: [
              {
                label: 'Romania', icon: 'fas fa-globe-europe'
              }
            ]
          }
        ]
      },
      {
        label: 'System', icon: 'pi ' + (this.lastState ? 'pi-cog' : 'pi-times'),
        items: [
          {
            label: 'Status',
            items: this.healthchecks
          }
        ]
      }
    ];
  }
}
