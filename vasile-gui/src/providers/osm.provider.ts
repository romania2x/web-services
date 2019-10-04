import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

@Injectable()
export class OsmProvider {
  public constructor(private httpClient: HttpClient) {
  }

  public fetchLayersProperties(): Observable<any> {
    return this.httpClient.get<any>('/api/osm/layers');
  }

  public fetchLayers(criteria: any): Observable<any[]> {
    return this.httpClient.post<any[]>('/api/osm/layers', criteria);
  }
}
