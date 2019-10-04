import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {Injectable} from '@angular/core';

@Injectable()
export class BaseLayersProvider {
  constructor(private httpClient: HttpClient) {
  }

  fetchBaseLayers(): Observable<any[]> {
    return this.httpClient.get<any[]>('/api/base-layers');
  }
}
