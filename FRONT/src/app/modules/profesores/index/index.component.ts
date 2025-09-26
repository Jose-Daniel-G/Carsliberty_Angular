import { Component, OnInit } from '@angular/core';
import Swal from 'sweetalert2';

import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ProfesorsService } from '../profesors.service';
import { Profesor } from '../profesor.interface';

@Component({
  selector: 'app-profesors',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './index.component.html',
})
export class IndexComponent implements OnInit {
  profesors: Profesor[] = [];

  constructor(private profesorsService: ProfesorsService) {}

  ngOnInit(): void {
    this.cargarProfesors();
  }

  cargarProfesors(): void {
    this.profesorsService.getProfesors().subscribe({
      next: (response) => { 
        this.profesors = response.profesors.data;
        console.log(this.profesors); // para verificar que llegó
      },
      error: (err) => console.error('Error cargando profesors', err)
    });
  }



  editarProfesor(profesor: Profesor): void {
    Swal.fire({
      title: 'Editar Profesor',
      html: `
        <input id="nombres" class="swal2-input" value="${profesor.nombres}" placeholder="Nombres">
        <input id="apellidos" class="swal2-input" value="${profesor.apellidos}" placeholder="Apellidos">
        <input id="cc" class="swal2-input" value="${profesor.telefono}" placeholder="telefono">
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      preConfirm: () => {
        return {
          ...profesor,
          nombres: (document.getElementById('nombres') as HTMLInputElement).value,
          apellidos: (document.getElementById('apellidos') as HTMLInputElement).value,
          cc: Number((document.getElementById('cc') as HTMLInputElement).value),
          direccion: (document.getElementById('direccion') as HTMLInputElement).value,
        };
      }
    }).then(result => {
      if (result.isConfirmed) {
        this.profesorsService.updateProfesor(profesor.id!, result.value).subscribe({
          next: () => {
            Swal.fire('Actualizado', 'El profesor ha sido editado', 'success');
            this.cargarProfesors();
          },
          error: (err) => {
            console.error('Error actualizando profesor', err);
            Swal.fire('Error', 'No se pudo actualizar el profesor', 'error');
          }
        });
      }
    });
  }

  eliminarProfesor(id: number): void {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¿Deseas eliminar este profesor?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        this.profesorsService.deleteProfesor(id).subscribe({
          next: () => {
            Swal.fire('Eliminado', 'Profesor eliminado correctamente', 'success');
            this.cargarProfesors();
          },
          error: (err) => {
            console.error('Error al eliminar profesor', err);
            Swal.fire('Error', 'No se pudo eliminar el profesor', 'error');
          }
        });
      }
    });
  }
}
