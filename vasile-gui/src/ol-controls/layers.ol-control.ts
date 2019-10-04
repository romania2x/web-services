import Control from 'ol/control/Control';
import {MapController} from '../controllers/map.controller';

export class LayersOlControl extends Control {
  constructor(mapController: MapController, options?) {
    options = options ? options : {};
    options.element = document.createElement('div');
    options.element.className = 'ol-unselectable ol-control';

    options.element.style.top = '10px';
    options.element.style.right = '.5em';


    const button = document.createElement('button');
    button.innerHTML = '<i class="fas fa-layer-group">';

    button.onclick = () => mapController.showBaseLayersModal();

    options.element.appendChild(button);

    super(options);
  }
}
