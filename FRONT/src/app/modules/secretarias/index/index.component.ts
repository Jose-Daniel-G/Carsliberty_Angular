import { Component, OnInit } from '@angular/core';
import Swal from 'sweetalert2';

import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { SecretariasService } from '../secretarias.service';
import { Secretaria } from '../secretaria.interface';

@Component({
  selector: 'app-secretarias',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './index.component.html',
})
export class IndexComponent implements OnInit {
  secretarias: Secretaria[] = [];

  constructor(private secretariasService: SecretariasService) {}

  ngOnInit(): void {
    this.cargarSecretarias();
  }

cargarSecretarias(): void {
  this.secretariasService.getSecretarias().subscribe({
    next: (response) => {
      // Aquí accedes a la propiedad 'secretaria' y luego a 'data'
      this.secretarias = response.secretarias.data;
      console.log(this.secretarias); // para verificar que llegó
    },
    error: (err) => console.error('Error cargando secretarias', err)
  });
}



  editarSecretaria(secretaria: Secretaria): void {
    Swal.fire({
      title: 'Editar Secretaria',
      html: `
        <input id="nombres" class="swal2-input" value="${secretaria.nombres}" placeholder="Nombres">
        <input id="apellidos" class="swal2-input" value="${secretaria.apellidos}" placeholder="Apellidos">
        <input id="cc" class="swal2-input" value="${secretaria.cc}" placeholder="Cédula">
        <input id="direccion" class="swal2-input" value="${secretaria.direccion}" placeholder="Dirección">
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      preConfirm: () => {
        return {
          ...secretaria,
          nombres: (document.getElementById('nombres') as HTMLInputElement).value,
          apellidos: (document.getElementById('apellidos') as HTMLInputElement).value,
          cc: Number((document.getElementById('cc') as HTMLInputElement).value),
          direccion: (document.getElementById('direccion') as HTMLInputElement).value,
        };
      }
    }).then(result => {
      if (result.isConfirmed) {
        this.secretariasService.updateSecretaria(secretaria.id!, result.value).subscribe({
          next: () => {
            Swal.fire('Actualizado', 'El secretaria ha sido editado', 'success');
            this.cargarSecretarias();
          },
          error: (err) => {
            console.error('Error actualizando secretaria', err);
            Swal.fire('Error', 'No se pudo actualizar el secretaria', 'error');
          }
        });
      }
    });
  }

  eliminarSecretaria(id: number): void {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¿Deseas eliminar este secretaria?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        this.secretariasService.deleteSecretaria(id).subscribe({
          next: () => {
            Swal.fire('Eliminado', 'Secretaria eliminado correctamente', 'success');
            this.cargarSecretarias();
          },
          error: (err) => {
            console.error('Error al eliminar secretaria', err);
            Swal.fire('Error', 'No se pudo eliminar el secretaria', 'error');
          }
        });
      }
    });
  }
}
