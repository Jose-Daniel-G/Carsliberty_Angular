import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-small-box',
  standalone: true,
  imports: [],
  templateUrl: './small-box.component.html',
  styleUrl: './small-box.component.css'
})
export class SmallBoxComponent {
  @Input() color: string = 'bg-info'; // Clase de color
  @Input() count: number = 0; // Número principal
  @Input() text: string = ''; // Texto secundario
  @Input() icon: string = 'bi-gear'; // Ícono de Bootstrap Icons
  @Input() link: string = '#'; // Enlace para el footer
  @Input() linkText: string = 'Más info'; // Texto del footer
}
