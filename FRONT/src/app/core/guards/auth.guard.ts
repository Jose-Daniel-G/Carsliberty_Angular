import { Injectable } from '@angular/core';
import { CanActivate, Router, ActivatedRouteSnapshot } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  constructor(private authService: AuthService, private router: Router) { }

  canActivate(route: ActivatedRouteSnapshot): boolean {
    const user = this.authService.getCurrentUser();

    if (!user) {
      this.router.navigate(['/auth/login']);
      return false;
    }
    // 3️⃣ Validar roles
    const requiredRoles: string[] = route.data?.['roles']; // Ej: ['admin']
    const userRoles: string[] = this.authService.getUserRoles(); // Ej: ['funcionario']

    if (requiredRoles && !userRoles.some(r => requiredRoles.includes(r))) {
      this.router.navigate(['/auth/login']); // No tiene los roles necesarios
      return false;
    }

    return true; // Tiene permisos
  }

}
