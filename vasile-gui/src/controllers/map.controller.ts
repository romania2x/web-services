import Map from 'ol/Map';
import TileLayer from 'ol/layer/Tile';
import XYZ from 'ol/source/XYZ';
import View from 'ol/View';
import {MapOptions} from 'ol/PluggableMap';
import ZoomSlider from 'ol/control/ZoomSlider';
import {LayersOlControl} from '../ol-controls/layers.ol-control';
import {DialogService} from 'primeng/api';
import {OLBaseLayersModalComponent} from '../modals/ol/base-layers.modal';
import OLCesium from 'olcs/OLCesium.js';
import {DrawingsOlControl} from '../ol-controls/drawings.ol-control';
import {OLDrawingControlsModalComponent} from '../modals/ol/drawing-controls.modal';
import Draw, {createBox, createRegularPolygon, DrawEvent} from 'ol/interaction/Draw';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import Style from 'ol/style/Style';
import Fill from 'ol/style/Fill';
import Stroke from 'ol/style/Stroke';
import CircleStyle from 'ol/style/Circle';
import Snap from 'ol/interaction/Snap';
import GeometryType from 'ol/geom/GeometryType';
import {OsmImportOlControl} from '../ol-controls/osm-import.ol-control';
import {OLOSMImportModalComponent} from "../modals/ol/osm-import.modal";

export class MapController {
  private mapInstance: Map;
  private olCesium: OLCesium;
  private baseLayer: TileLayer;

  private readonly vectorSource: VectorSource;
  private readonly vectorLayer: VectorLayer;

  private drawInteraction: Draw;
  private snapInteraction: Snap;

  constructor(private target: HTMLElement, private dialogService: DialogService) {
    this.vectorSource = new VectorSource();
    this.vectorLayer = new VectorLayer({
      source: this.vectorSource,
      style: new Style({
        fill: new Fill({
          color: 'rgba(255, 255, 255, 0.2)'
        }),
        stroke: new Stroke({
          color: '#ffcc33',
          width: 2
        }),
        image: new CircleStyle({
          radius: 7,
          fill: new Fill({
            color: '#ffcc33'
          })
        })
      })
    });

    this.mapInstance = new Map({
      target: this.target,
      layers: [],
      view: new View({
        center: [0, 0],
        zoom: 1
      })
    } as MapOptions);

    this.mapInstance.addControl(new ZoomSlider());
    this.mapInstance.addControl(new LayersOlControl(this));
    this.mapInstance.addControl(new DrawingsOlControl(this));
    this.mapInstance.addControl(new OsmImportOlControl(this));

    // sandbox cesium
    // this.olCesium = new OLCesium({map: this.mapInstance});
    // this.olCesium.setEnabled(true);

    this.setBaseLayer({
      title: 'Stamen toner background',
      url: 'http://public.vasile/mapproxy/tiles/1.0.0/stamen_toner_background/webmercator/{z}/{x}/{y}.png'
    });

    this.mapInstance.addLayer(this.vectorLayer);
    this.vectorLayer.setZIndex(1);
  }

  showBaseLayersModal() {
    this.dialogService.open(
      OLBaseLayersModalComponent, {
        header: 'Choose base layer',
        width: '300px',
        data: {
          controller: this
        }
      }
    );
  }

  showDrawingControls() {
    this.dialogService.open(
      OLDrawingControlsModalComponent,
      {
        header: 'Choose your weapon',
        width: '300px',
        data: {
          controller: this
        }
      }
    );
  }

  showOsmImport() {
    this.dialogService.open(
      OLOSMImportModalComponent,
      {
        header: 'Import from OpenStreetMap Database',
        width: '90%',
        data: {
          controller: this
        }
      }
    );
  }

  startDrawing(type: string, freeHand = false) {
    this.resetCurrentDrawing();
    switch (type) {
      case GeometryType.POINT:
      case GeometryType.LINE_STRING:
      case GeometryType.POLYGON:
      case GeometryType.CIRCLE:
        this.drawInteraction = new Draw({
          source: this.vectorSource,
          type: type as GeometryType,
          freehand: freeHand
        });
        break;
      case 'Box':
        this.drawInteraction = new Draw({
          source: this.vectorSource,
          type: GeometryType.CIRCLE,
          geometryFunction: createBox()
        });
        break;
      case 'Square':
        this.drawInteraction = new Draw({
          source: this.vectorSource,
          type: GeometryType.CIRCLE,
          geometryFunction: createRegularPolygon(4)
        });
        break;
    }
    this.snapInteraction = new Snap({
      source: this.vectorSource
    });
    this.mapInstance.addInteraction(this.drawInteraction);
    this.mapInstance.addInteraction(this.snapInteraction);

    this.drawInteraction.on('drawend', (event: DrawEvent) => {
      console.log(event.feature.getGeometry());
    });
  }

  resetCurrentDrawing() {
    if (this.drawInteraction) {
      this.mapInstance.removeInteraction(this.drawInteraction);
      this.mapInstance.removeInteraction(this.snapInteraction);
    }
  }

  setBaseLayer(layer) {
    if (this.baseLayer) {
      this.mapInstance.removeLayer(this.baseLayer);
    }
    this.baseLayer = new TileLayer({
      source: new XYZ({
        url: layer.url,
        wrapX: false
      })
    });
    this.mapInstance.addLayer(this.baseLayer);
    this.baseLayer.setZIndex(0);
  }

  getBaseLayerUrl(): string | null {
    return this.baseLayer ? (this.baseLayer.getSource() as XYZ).getUrls()[0] : null;
  }
}
