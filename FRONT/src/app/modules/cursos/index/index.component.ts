import { Component, OnInit } from '@angular/core';
import Swal from 'sweetalert2';

import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CursosService } from '../cursos.service';
import { Curso } from '../curso.interface';

@Component({
  selector: 'app-cursos',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './index.component.html',
})
export class IndexComponent implements OnInit {
  cursos: Curso[] = [];

  constructor(private cursosService: CursosService) {}

  ngOnInit(): void {
    this.cargarCursos();
  }

  cargarCursos(): void {
    this.cursosService.getCursos().subscribe({
      next: (response) => { 
        this.cursos = response.cursos.data;
        console.log(this.cursos); // para verificar que llegó
      },
      error: (err) => console.error('Error cargando cursos', err)
    });
  }

  editarCurso(curso: Curso): void {
    Swal.fire({
      title: 'Editar Curso',
      html: `
        <input id="nombres" class="swal2-input" value="${curso.nombre}" placeholder="Nombres">
        <input id="apellidos" class="swal2-input" value="${curso.descripcion}" placeholder="Apellidos">
        <input id="cc" class="swal2-input" value="${curso.horas_requeridas}" placeholder="telefono">
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      preConfirm: () => {
        return {
          ...curso,
          nombres: (document.getElementById('nombres') as HTMLInputElement).value,
          apellidos: (document.getElementById('apellidos') as HTMLInputElement).value,
          cc: Number((document.getElementById('cc') as HTMLInputElement).value),
          direccion: (document.getElementById('direccion') as HTMLInputElement).value,
        };
      }
    }).then(result => {
      if (result.isConfirmed) {
        this.cursosService.updateCurso(curso.id!, result.value).subscribe({
          next: () => {
            Swal.fire('Actualizado', 'El curso ha sido editado', 'success');
            this.cargarCursos();
          },
          error: (err) => {
            console.error('Error actualizando curso', err);
            Swal.fire('Error', 'No se pudo actualizar el curso', 'error');
          }
        });
      }
    });
  }

  eliminarCurso(id: number): void {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¿Deseas eliminar este curso?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        this.cursosService.deleteCurso(id).subscribe({
          next: () => {
            Swal.fire('Eliminado', 'Curso eliminado correctamente', 'success');
            this.cargarCursos();
          },
          error: (err) => {
            console.error('Error al eliminar curso', err);
            Swal.fire('Error', 'No se pudo eliminar el curso', 'error');
          }
        });
      }
    });
  }
}
