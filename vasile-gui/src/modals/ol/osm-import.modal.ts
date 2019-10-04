import {AfterViewInit, Component, ElementRef, ViewChild} from '@angular/core';
import {MapController} from "../../controllers/map.controller";
import {DialogService, TreeNode} from "primeng/api";
import {OsmProvider} from "../../providers/osm.provider";

@Component({
  moduleId: 'app-modal-ol-osm-import',
  template: `
      <div class="row" style="min-height: 500px">
          <div class="col-6">
              <p-tabView>
                  <p-tabPanel header="Criteria">
                      <p-scrollPanel [style]="{width:'100%',height:'500px'}">
                          <p-treeTable [value]="criteria">
                              <ng-template pTemplate="body" let-rowNode let-rowData="rowData">
                                  <tr>
                                      <td>
                                          <p-treeTableToggler [rowNode]="rowNode"></p-treeTableToggler>
                                          {{rowData.name}}
                                      </td>
                                      <td align="center">
                                          <p-multiSelect *ngIf="rowData.type && rowData.type == 'options'"
                                                         [style]="{width:'100%'}"
                                                         [options]="rowData.options"
                                                         [(ngModel)]="rowData.value"></p-multiSelect>
                                          <input *ngIf="rowData.type && rowData.type == 'query'" type="text"
                                                 [(ngModel)]="rowData.value"
                                                 class="form-control bg-dark text-light"/>
                                          <p-inputSwitch *ngIf="!rowData.type"
                                                         [(ngModel)]="rowData.selected"></p-inputSwitch>
                                      </td>
                                  </tr>
                              </ng-template>
                          </p-treeTable>
                      </p-scrollPanel>
                  </p-tabPanel>
                  <p-tabPanel header="Results"></p-tabPanel>
              </p-tabView>
          </div>
          <div class="col-6">
              <div #mapTarget style='width:100%;height:100%;display:block;'></div>
          </div>
          <div class="col-12 mt-3 mb-3">
              <button class="btn btn-primary" (click)="searchOSM()">Search <i class="fas fa-search fa-fw"></i></button>
          </div>
      </div>
  `
})
export class OLOSMImportModalComponent implements AfterViewInit {
  @ViewChild('mapTarget', {static: false}) mapTarget: ElementRef;
  criteria: TreeNode[] = [];

  private olController: MapController;

  constructor(private dialogService: DialogService, private osmProvider: OsmProvider) {
  }

  ngAfterViewInit(): void {
    this.olController = new MapController(this.mapTarget.nativeElement, this.dialogService);

    this.osmProvider.fetchLayersProperties().subscribe(data => {
      this.buildMenu(data);
    });
  }

  public searchOSM() {
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
      console.log(results);
    });
  }

  private buildMenu(data) {
    this.criteria = [
      {
        data: {
          name: 'Points',
          selected: false,
          layer: 'point'
        },
        leaf: false,
        children: data.point.map(property => this.buildMenuItem(property))
      },
      {
        data: {
          name: 'Lines',
          selected: false,
          layer: 'line'
        },
        leaf: false,
        children: data.line.map(property => this.buildMenuItem(property))
      },
      {
        data: {
          name: 'Polygons',
          selected: false,
          layer: 'polygon'
        },
        leaf: false,
        children: data.polygon.map(property => this.buildMenuItem(property))
      },
      {
        data: {
          name: 'Roads',
          selected: false,
          layer: 'road'
        },
        leaf: false,
        children: data.road.map(property => this.buildMenuItem(property))
      }
    ];
  }

  private buildMenuItem(property) {
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
