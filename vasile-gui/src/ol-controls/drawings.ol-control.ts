import Control from 'ol/control/Control';
import {MapController} from '../controllers/map.controller';

export class DrawingsOlControl extends Control {
  constructor(mapController: MapController, options?) {
    options = options ? options : {};
    options.element = document.createElement('div');
    options.element.className = 'ol-unselectable ol-control';

    options.element.style.top = '.5em';
    options.element.style.left = '.5em';


    const button = document.createElement('button');
    button.innerHTML = '<i class="fas fa-plus"></i>';

    button.onclick = () => mapController.showDrawingControls();

    options.element.appendChild(button);

    super(options);
  }
}
