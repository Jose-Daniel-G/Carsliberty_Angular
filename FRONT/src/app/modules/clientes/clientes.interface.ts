// src/app/clientes/interfaces/cliente.interface.ts

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
}
