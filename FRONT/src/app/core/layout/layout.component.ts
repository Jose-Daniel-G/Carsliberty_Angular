import { Component, AfterViewInit } from '@angular/core';
import { Router, RouterLink, RouterOutlet } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { AdminLteService } from '../services/admin-lte.service';

import { NgIf } from '@angular/common';
import { AuthUser } from '../models/login.model';

@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [RouterLink, RouterOutlet, NgIf],
  templateUrl: './layout.component.html',
  styleUrl: './layout.component.css',
})
export class LayoutComponent {
  user: AuthUser | null = null; // Variable para almacenar el usuario

  constructor(
    public authService: AuthService,
    private adminLte: AdminLteService,
    private router: Router
  ) {
    this.user = this.authService.getCurrentUser(); // Asigna el usuario después de que el servicio esté inicializado
  }

  ngAfterViewInit(): void {
    const toggleBtn = document.querySelector('[data-lte-toggle="sidebar"]');

    toggleBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      this.adminLte.toggleSidebar(); // mejor usar el servicio
    });

    document.querySelectorAll('.nav-item.has-treeview > a').forEach((link) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const parent = link.closest('.nav-item.has-treeview');
        parent?.classList.toggle('menu-open');
      });
    });
  }

  logout() {
        // Limpiar localStorage por seguridad
        localStorage.removeItem('user');
        localStorage.removeItem('access_token');
        this.router.navigate(['/login']);
  }
}
