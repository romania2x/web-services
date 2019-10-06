import {AfterViewInit, Component, ElementRef, ViewChild} from '@angular/core';
import {MapController} from '../../../controllers/map.controller';
import {DialogService, TreeNode} from 'primeng/api';
import {OsmProvider} from '../../../providers/osm.provider';
import VectorSource from 'ol/source/Vector';
import GeoJSON from 'ol/format/GeoJSON';
import VectorLayer from 'ol/layer/Vector';
import Style from "ol/style/Style";
import Circle from "ol/geom/Circle";
import Fill from "ol/style/Fill";
import Stroke from "ol/style/Stroke";
import Text from "ol/style/Text";

@Component({
  moduleId: 'app-modal-ol-osm-import',
  templateUrl: './osm-import.modal.html'
})
export class OLOSMImportModalComponent implements AfterViewInit {
  @ViewChild('mapTarget', {static: false}) mapTarget: ElementRef;
  criteria: TreeNode[] = [];
  loadingState = false;

  private olController: MapController;
  private resultVectorLayer: VectorLayer;
  private resultVectorSource: VectorSource;

  constructor(private dialogService: DialogService, private osmProvider: OsmProvider) {
  }

  ngAfterViewInit(): void {
    this.olController = new MapController(this.mapTarget.nativeElement, this.dialogService);
    this.olController
      .enableSelection((e) => {
        console.log('Got OSM selection', e);
      })
      .enableHovering();

    this.resultVectorSource = new VectorSource({
      features: []
    });

    this.resultVectorLayer = new VectorLayer({
      source: this.resultVectorSource,
      style: new Style({
        fill: new Fill({color: '#06a22a'}),
        stroke: new Stroke({color: '#06a22a'}),
        text: new Text({fill: new Fill({color: '#06a22a'})})
      }),
    });

    this.olController.getMap().addLayer(this.resultVectorLayer);

    this.osmProvider.fetchLayersProperties().subscribe(data => {
      this.buildLayersSelection(data);
    });
  }

  /**
   * Perform OSM Search
   */
  public searchOSM() {

    this.loadingState = true;

    const criteria: any = {};
    this.criteria.map((layer: any) => {
      if (layer.data.selected) {
        criteria[layer.data.layer] = {};

        layer.children.map(child => {
          switch (child.data.type) {
            case 'query':
              if (child.data.value != null) {
                criteria[layer.data.layer][child.data.name] = child.data.value;
              }
              break;
            case 'options':
              if (child.data.value != null && child.data.value.length > 0) {
                criteria[layer.data.layer][child.data.name] = child.data.value;
              }
              break;
          }
        });
      }
    });

    this.osmProvider.fetchLayers(criteria).subscribe(results => {
      this.resultVectorSource.forEachFeature((feature) => {
        this.resultVectorSource.removeFeature(feature);
      });

      results.forEach(feature => {
        this.resultVectorSource.addFeature(
          (new GeoJSON()).readFeature(
            feature,
            {
              featureProjection: 'EPSG:3857',
            }
          )
        );
      });

      if (results.length > 0) {
        this.olController.getMap().getView().fit(
          this.resultVectorLayer.getSource().getExtent()
        );
      }

      this.loadingState = false;
    });
  }

  /**
   * Build layers selection criteria
   */
  private buildLayersSelection(data) {
    this.criteria = [
      {
        data: {
          name: 'Points',
          selected: false,
          layer: 'point'
        },
        leaf: false,
        children: data.point.map(property => this.buildLayerPropertyMenuItem(property))
      },
      {
        data: {
          name: 'Lines',
          selected: false,
          layer: 'line'
        },
        leaf: false,
        children: data.line.map(property => this.buildLayerPropertyMenuItem(property))
      },
      {
        data: {
          name: 'Polygons',
          selected: false,
          layer: 'polygon'
        },
        leaf: false,
        children: data.polygon.map(property => this.buildLayerPropertyMenuItem(property))
      },
      {
        data: {
          name: 'Roads',
          selected: false,
          layer: 'road'
        },
        leaf: false,
        children: data.road.map(property => this.buildLayerPropertyMenuItem(property))
      }
    ];
  }

  /**
   * Build MenuItem based on layer property
   */
  private buildLayerPropertyMenuItem(property) {
    switch (property.type) {
      case 'options':
        return {
          data: {
            name: property.name,
            type: property.type,
            options: property.options.map(option => {
              return {label: option, value: option};
            }),
            value: null
          },
          leaf: true
        };
      case 'query':
        return {
          data: {
            name: property.name,
            type: property.type,
            value: null
          },
          leaf: true
        };
    }
  }
}
