import {Component, OnInit} from '@angular/core';
import {DynamicDialogConfig} from 'primeng/api';
import {BaseLayersProvider} from '../../providers/base-layers.provider';
import {MapController} from '../../controllers/map.controller';

@Component({
  moduleId: 'app-modals-ol-layers',
  template: `
      <p-listbox [options]='baseLayers' [(ngModel)]='selectedBaseLayer' [style]='{width:"100%"}'
                 (onChange)='setBaseLayer($event)'></p-listbox>
  `
})
export class OLBaseLayersModalComponent implements OnInit {
  baseLayers = [];
  selectedBaseLayer = null;
  private mapController: MapController;

  constructor(private modalConfig: DynamicDialogConfig, private baseLayersProvider: BaseLayersProvider) {
  }

  ngOnInit(): void {
    this.mapController = this.modalConfig.data.controller;

    this.baseLayersProvider.fetchBaseLayers().subscribe(layers => {
      const currentBaseLayer = this.mapController.getBaseLayerUrl();
      this.baseLayers = layers.map(layer => {
        if (layer.url === currentBaseLayer) {
          this.selectedBaseLayer = layer;
        }
        return {label: layer.title, value: layer};
      });
    });
  }

  setBaseLayer(event) {
    this.mapController.setBaseLayer(event.value);
  }
}
