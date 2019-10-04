import Control from 'ol/control/Control';
import {MapController} from '../controllers/map.controller';

export class OsmImportOlControl extends Control {
  constructor(mapController: MapController, options?) {

    options = options ? options : {};
    options.element = document.createElement('div');
    options.element.className = 'ol-unselectable ol-control';

    options.element.style.top = '60px';
    options.element.style.left = '.5em';


    const button = document.createElement('button');
    button.innerHTML = '<img src="assets/osm.svg" width="30px" height="30px" title="Import from OpenStreetMap Database"/>';

    button.onclick = () => mapController.showOsmImport();

    options.element.appendChild(button);

    super(options);
  }
}
