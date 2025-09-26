import { Component, OnInit, ViewChild } from '@angular/core';
import { Profesor } from '../profesor.interface';
import { ProfesorsService } from '../profesors.service';
import { CreateComponent } from '../create/create.component';
import Swal from 'sweetalert2';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-profesors',
  standalone: true,
  imports: [CommonModule, RouterModule, CreateComponent], // <--- agregar RouterModule aquí
  templateUrl: './index.component.html',
})
export class IndexComponent implements OnInit {
  @ViewChild('createComponent') createComponent!: CreateComponent;
  profesors: Profesor[] = [];

  constructor(private profesorsService: ProfesorsService) {}

  ngOnInit(): void {
    this.cargarProfesors();
  }

  cargarProfesors(): void {
    this.profesorsService.getProfesors().subscribe({
      next: (response) => (this.profesors = response.profesors.data),
      error: (err) => console.error(err)
    });
  }

  crearProfesor() {
    this.createComponent.profesorData = null; // No hay datos → crear
    this.createComponent.openModal('create');
  }

  editarProfesor(profesor: Profesor) {
    this.createComponent.profesorData = profesor; // Pasamos los datos
    this.createComponent.openModal('edit', profesor);
  }

  eliminarProfesor(id: number) {
    Swal.fire({
      title: '¿Deseas eliminar este profesor?',
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
          error: (err) => Swal.fire('Error', 'No se pudo eliminar', 'error')
        });
      }
    });
  }
}
