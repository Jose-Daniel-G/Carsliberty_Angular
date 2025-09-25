import { Component, OnInit } from '@angular/core';
import Swal from 'sweetalert2';

import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ClientesService } from '../clientes.service';
import { Cliente } from '../clientes.interface';

@Component({
  selector: 'app-clientes',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './index.component.html',
})
export class IndexComponent implements OnInit {
  clientes: Cliente[] = [];

  constructor(private clientesService: ClientesService) {}

  ngOnInit(): void {
    this.cargarClientes();
  }

  cargarClientes(): void {
    this.clientesService.getClientes().subscribe({
      next: (data) => this.clientes = data,
      error: (err) => console.error('Error cargando clientes', err)
    });
  }

  editarCliente(cliente: Cliente): void {
    Swal.fire({
      title: 'Editar Cliente',
      html: `
        <input id="nombres" class="swal2-input" value="${cliente.nombres}" placeholder="Nombres">
        <input id="apellidos" class="swal2-input" value="${cliente.apellidos}" placeholder="Apellidos">
        <input id="cc" class="swal2-input" value="${cliente.cc}" placeholder="Cédula">
        <input id="direccion" class="swal2-input" value="${cliente.direccion}" placeholder="Dirección">
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      preConfirm: () => {
        return {
          ...cliente,
          nombres: (document.getElementById('nombres') as HTMLInputElement).value,
          apellidos: (document.getElementById('apellidos') as HTMLInputElement).value,
          cc: Number((document.getElementById('cc') as HTMLInputElement).value),
          direccion: (document.getElementById('direccion') as HTMLInputElement).value,
        };
      }
    }).then(result => {
      if (result.isConfirmed) {
        this.clientesService.updateCliente(cliente.id!, result.value).subscribe({
          next: () => {
            Swal.fire('Actualizado', 'El cliente ha sido editado', 'success');
            this.cargarClientes();
          },
          error: (err) => {
            console.error('Error actualizando cliente', err);
            Swal.fire('Error', 'No se pudo actualizar el cliente', 'error');
          }
        });
      }
    });
  }

  eliminarCliente(id: number): void {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¿Deseas eliminar este cliente?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        this.clientesService.deleteCliente(id).subscribe({
          next: () => {
            Swal.fire('Eliminado', 'Cliente eliminado correctamente', 'success');
            this.cargarClientes();
          },
          error: (err) => {
            console.error('Error al eliminar cliente', err);
            Swal.fire('Error', 'No se pudo eliminar el cliente', 'error');
          }
        });
      }
    });
  }
}
