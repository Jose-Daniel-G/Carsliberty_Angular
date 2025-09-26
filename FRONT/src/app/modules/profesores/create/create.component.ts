import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ProfesorsService } from '../profesors.service';
import Swal from 'sweetalert2';
import { Modal } from 'bootstrap';
import { CommonModule } from '@angular/common';
@Component({
  selector: 'app-create',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.css']
})
export class CreateComponent implements OnInit {
  profesorForm!: FormGroup;
  private modalInstance!: Modal;

  @ViewChild('createProfesorModal') modalElement!: ElementRef;
  constructor(private fb: FormBuilder, private profesorsService: ProfesorsService) {}

  ngOnInit(): void {
    this.profesorForm = this.fb.group({
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      telefono: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
      password_confirmation: ['', Validators.required]
    });
  }
  ngAfterViewInit() {
    // Inicializar el modal de Bootstrap
    this.modalInstance = new Modal(this.modalElement.nativeElement);
  }
    openModal() {
    this.modalInstance.show();
  }

  closeModal() {
    this.modalInstance.hide();
  }
  submit() {
    if (this.profesorForm.invalid) {
      this.profesorForm.markAllAsTouched();
      return;
    }

    this.profesorsService.createProfesor(this.profesorForm.value).subscribe({
      next: () => {
        Swal.fire('Éxito', 'Profesor creado correctamente', 'success');
        this.profesorForm.reset();
        this.closeModal(); // cerrar modal correctamente
      },
      error: (err) => {
        Swal.fire('Error', err.error.message || 'Hubo un problema al crear el profesor', 'error');
      }
    });
  }

  get f() { return this.profesorForm.controls; }
}
