import { Component, OnInit, ViewChild, ElementRef, Input } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ProfesorsService } from '../profesors.service';
import Swal from 'sweetalert2';
import { Modal } from 'bootstrap';
import { CommonModule } from '@angular/common';
import { Profesor } from '../profesor.interface';
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
  @Input() profesorData: Profesor | null = null; // Datos para editar
  modo: 'create' | 'edit' = 'create';
  constructor(private fb: FormBuilder, private profesorsService: ProfesorsService) { }

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
  ngAfterViewInit() {  // Inicializar el modal de Bootstrap
    this.modalInstance = new Modal(this.modalElement.nativeElement);
  }
  openModal(modo: 'create' | 'edit', profesor?: Profesor) {
    this.modo = modo;

    if (modo === 'edit' && profesor) {
      this.profesorData = profesor; // <--- asignar aquí
      this.profesorForm.patchValue({
        nombres: profesor.nombres,
        apellidos: profesor.apellidos,
        telefono: profesor.telefono,
        email: profesor.user?.email || '',
        password: '',
        password_confirmation: ''
      });

      // Quitar validación de contraseña en edición
      this.profesorForm.get('password')?.clearValidators();
      this.profesorForm.get('password_confirmation')?.clearValidators();
      this.profesorForm.get('password')?.updateValueAndValidity();
      this.profesorForm.get('password_confirmation')?.updateValueAndValidity();
    } else {
      this.profesorData = null;
      this.profesorForm.reset();

      // En creación, contraseña obligatoria
      this.profesorForm.get('password')?.setValidators(Validators.required);
      this.profesorForm.get('password_confirmation')?.setValidators(Validators.required);
      this.profesorForm.get('password')?.updateValueAndValidity();
      this.profesorForm.get('password_confirmation')?.updateValueAndValidity();
    }


    this.modalInstance.show();
  }

  closeModal() {
    this.modalInstance.hide();
    this.profesorData = null; // resetear para la próxima apertura
    this.profesorForm.reset();
  }
  submit() {
    if (this.profesorForm.invalid) {
      this.profesorForm.markAllAsTouched();
      return;
    }

    const formData: any = {
      nombres: this.f['nombres'].value,
      apellidos: this.f['apellidos'].value,
      telefono: this.f['telefono'].value,
      email: this.f['email'].value
    };

    // Solo enviar password si fue ingresada
    if (this.f['password'].value) {
      formData.password = this.f['password'].value;
      formData.password_confirmation = this.f['password_confirmation'].value;
    }

    if (this.modo === 'create') {
      this.profesorsService.createProfesor(formData).subscribe({
        next: () => {
          Swal.fire('Éxito', 'Profesor creado correctamente', 'success');
          this.closeModal();
        }
      });
    } else if (this.modo === 'edit' && this.profesorData) {
      this.profesorsService.updateProfesor(this.profesorData.id!, formData).subscribe({
        next: () => {
          Swal.fire('Éxito', 'Profesor actualizado correctamente', 'success');
          this.closeModal();
        },
        error: (err) => {
          console.error('Error actualizando profesor:', err);
          Swal.fire('Error', err.error?.message || 'No se pudo actualizar el profesor', 'error');
        }
      });
    }
  } 
  get f() { return this.profesorForm.controls; }
}
