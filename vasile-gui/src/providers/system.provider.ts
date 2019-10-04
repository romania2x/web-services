import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';


@Injectable()
export class SystemProvider {
  public constructor(private httpClient: HttpClient) {
  }

  public fetchAPIHealthcheck(): Observable<any[]> {
    return this.httpClient.get<any[]>('/api/healthcheck');
  }
}
