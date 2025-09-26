// src/app/clientes/cliente.interface.ts
import { User } from '../../core/models/user.interface';

export interface Profesor {
  id: number;
  nombres: string;
  apellidos: string;
  telefono: string;
  email: string;
  user: User;  // <- agregado
}

export interface PaginatedProfesors {
  current_page: number;
  data: Profesor[];
  per_page: number;
  last_page: number;
  total: number;
  // puedes agregar otros campos si los necesitas
}
export interface ProfesorsResponse {
  profesors: PaginatedProfesors;
}
