import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {LoginComponent} from '../components/login.component';

import {GoldenLayoutModule, GoldenLayoutConfiguration} from '@embedded-enterprises/ng6-golden-layout';
import * as $ from 'jquery';
import {DashboardComponent} from '../components/dashboard.component';
import {DashboardWelcomeComponent} from '../components/dashboard/welcome.component';
import {DashboardCesiumComponent} from '../components/dashboard/cesium.component';
import {AngularCesiumModule} from 'angular-cesium';
import {DashboardLeafletComponent} from '../components/dashboard/leaflet.component';
import {LeafletModule} from '@asymmetrik/ngx-leaflet';
import {
  FieldsetModule, InputSwitchModule,
  ListboxModule,
  MenubarModule, MultiSelectModule,
  PanelMenuModule, ScrollPanelModule,
  TabViewModule,
  TreeTableModule
} from 'primeng/primeng';
import {SecurityService} from '../services/security.service';
import {DashboardStatusComponent} from '../components/dashboard/status.component';
import {SystemProvider} from '../providers/system.provider';
import {HttpClientModule} from '@angular/common/http';
import {ProjectsComponent} from '../components/dashboard/projects.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {DynamicDialogModule} from 'primeng/dynamicdialog';
import {OLBaseLayersModalComponent} from '../modals/ol/base-layers.modal';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {BaseLayersProvider} from '../providers/base-layers.provider';
import {OLDrawingControlsModalComponent} from '../modals/ol/drawing-controls.modal';
import {OLOSMImportModalComponent} from '../modals/ol/osm-import/osm-import.modal';
import {OsmProvider} from '../providers/osm.provider';
import {TableModule} from "primeng/table";


window['$'] = $;

const config: GoldenLayoutConfiguration = {
  components: [
    {
      component: DashboardWelcomeComponent,
      componentName: 'welcome'
    },
    {
      component: DashboardCesiumComponent,
      componentName: 'cesium'
    },
    {
      component: DashboardLeafletComponent,
      componentName: 'leaflet'
    },
    {
      component: DashboardStatusComponent,
      componentName: 'status'
    },
    {
      component: ProjectsComponent,
      componentName: 'projects'
    }
  ],
  defaultLayout: {
    content: [
      {
        type: 'row',
        content: [
          // {
          //   type: 'component',
          //   componentName: 'welcome'
          // },
          // {
          //   type: 'component',
          //   componentName: 'cesium'
          // },
          {
            type: 'component',
            componentName: 'leaflet'
          },
          // {
          //   type: 'component',
          //   componentName: 'status',
          //   componentState: {
          //     title: 'Status'
          //   }
          // }
        ]
      }
    ]
  }
};

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    DashboardComponent,
    DashboardWelcomeComponent,
    DashboardCesiumComponent,
    DashboardLeafletComponent,
    DashboardStatusComponent,
    ProjectsComponent,
    OLBaseLayersModalComponent,
    OLDrawingControlsModalComponent,
    OLOSMImportModalComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    AppRoutingModule,
    GoldenLayoutModule.forRoot(config),
    AngularCesiumModule.forRoot(),
    LeafletModule.forRoot(),
    // primeng modules
    CommonModule,
    MenubarModule,
    DynamicDialogModule,
    ListboxModule,
    PanelMenuModule,
    TabViewModule,
    TreeTableModule,
    ScrollPanelModule,
    MultiSelectModule,
    InputSwitchModule,
    TableModule
  ],
  providers: [
    SystemProvider,
    BaseLayersProvider,
    SecurityService,
    OsmProvider
  ],
  bootstrap: [AppComponent],
  entryComponents: [
    DashboardWelcomeComponent,
    DashboardCesiumComponent,
    DashboardLeafletComponent,
    DashboardStatusComponent,
    ProjectsComponent,
    OLBaseLayersModalComponent,
    OLDrawingControlsModalComponent,
    OLOSMImportModalComponent
  ]
})
export class AppModule {
}
