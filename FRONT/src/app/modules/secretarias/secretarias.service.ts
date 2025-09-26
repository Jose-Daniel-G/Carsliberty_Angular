import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Secretaria, SecretariasResponse } from './secretaria.interface';

@Injectable({
  providedIn: 'root'
})
export class SecretariasService {
  private baseUrl = environment.URL_SERVICIOS; // Ejemplo: http://127.0.0.1:8000/api
  private apiUrl  = `${this.baseUrl}/secretarias`;

  constructor(private http: HttpClient, private router: Router) { }
  getSecretarias(): Observable<SecretariasResponse> {
    return this.http.get<SecretariasResponse>(this.apiUrl);
  }

  getSecretaria(id: number): Observable<Secretaria> {
    return this.http.get<Secretaria>(`${this.apiUrl}/${id}`);
  }

  createSecretaria(secretaria: Secretaria): Observable<Secretaria> {
    return this.http.post<Secretaria>(this.apiUrl, secretaria);
  }

  updateSecretaria(id: number, secretaria: Secretaria): Observable<Secretaria> {
    return this.http.put<Secretaria>(`${this.apiUrl}/${id}`, secretaria);
  }

  deleteSecretaria(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
}
