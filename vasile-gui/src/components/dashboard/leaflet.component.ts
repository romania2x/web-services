import {AfterViewInit, Component, ElementRef, ViewChild} from '@angular/core';

import {MapController} from '../../controllers/map.controller';
import {DialogService} from 'primeng/api';

@Component({
  moduleId: 'app-dashboard-leaflet',
  template: `
      <div #mapTarget style='width:100%;height:100%;display:block;'></div>
  `,
  providers: [DialogService]
})
export class DashboardLeafletComponent implements AfterViewInit {
  @ViewChild('mapTarget', {static: false}) mapTarget: ElementRef;
  private olController: MapController;

  constructor(private dialogService: DialogService) {
  }

  ngAfterViewInit(): void {
    this.olController = new MapController(this.mapTarget.nativeElement, this.dialogService);
  }
}
