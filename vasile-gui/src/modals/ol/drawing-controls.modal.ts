import {Component} from '@angular/core';
import {DynamicDialogConfig, DynamicDialogRef, MenuItem} from 'primeng/api';

@Component({
  moduleId: 'app-modals-ol-drawing-controls',
  template: `
      <p-panelMenu [model]='items' [style]="{width:'270px'}" [multiple]="false"></p-panelMenu>`
})
export class OLDrawingControlsModalComponent {
  items: MenuItem[] = [
    {
      label: 'Vector drawing',
      items: [
        {
          label: 'Point',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('Point');
            this.dialogRef.close();
          }
        },
        {
          label: 'Line',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('LineString');
            this.dialogRef.close();
          }
        },
        {
          label: 'Polygon',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('Polygon');
            this.dialogRef.close();
          }
        }
      ]
    },
    {
      label: 'Freehand drawing',
      items: [
        {
          label: 'Line',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('LineString', true);
            this.dialogRef.close();
          }
        },
        {
          label: 'Polygon',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('Polygon', true);
            this.dialogRef.close();
          }
        }
      ]
    },
    {
      label: 'Shapes',
      items: [
        {
          label: 'Square',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('Square');
            this.dialogRef.close();
          }
        },
        {
          label: 'Box',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('Box');
            this.dialogRef.close();
          }
        },
        {
          label: 'Circle',
          command: () => {
            this.dialogConfig.data.controller.startDrawing('Circle');
            this.dialogRef.close();
          }
        }
      ]
    }
  ];

  public constructor(private dialogRef: DynamicDialogRef, private dialogConfig: DynamicDialogConfig) {
  }
}
