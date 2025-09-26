// src/app/clientes/cliente.interface.ts
import { User } from '../../core/models/user.interface';

export interface Cliente {
  id: number;
  nombres: string;
  apellidos: string;
  cc: number;
  genero:string;
  celular: number;
  direccion:string;
  contacto_emergencia: number;
  observaciones: string;
  user: User;  // <- agregado
}

export interface PaginatedClientes {
  current_page: number;
  data: Cliente[];
  per_page: number;
  last_page: number;
  total: number;
  // puedes agregar otros campos si los necesitas
}
export interface ClientesResponse {
  clientes: PaginatedClientes;
  cursos: any[];
}
