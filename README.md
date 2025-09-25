`URL: `https://jose-daniel-g.github.io/1_store_angular/

# 🚀 Dynamic Web Application - Laravel + Angular SPA

> **Single Page Application (SPA)** con Laravel como API backend y Angular como frontend dinámico

| Backend | Frontend |
|---------|----------|
| ![Laravel](https://img.shields.io/badge/Laravel-v10.48.29-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) | ![Angular](https://img.shields.io/badge/Angular-v18.2.21-DD0031?style=for-the-badge&logo=angular&logoColor=white) |
| ![PHP](https://img.shields.io/badge/PHP-v8.2.12+-777BB4?style=for-the-badge&logo=php&logoColor=white) | ![TypeScript](https://img.shields.io/badge/TypeScript-v5.5.4-3178C6?style=for-the-badge&logo=typescript&logoColor=white) |

## 📋 Descripción del Proyecto

Esta aplicación web moderna utiliza **Laravel** como API REST backend y **Angular** como frontend SPA, proporcionando una experiencia de usuario fluida y dinámica sin recargas de página.

### ✨ Características Principales

- 🔥 **Single Page Application (SPA)** - Navegación sin recargas
- 🔐 **Autenticación JWT** - Sistema seguro de login
- 📱 **Responsive Design** - Adaptable a todos los dispositivos  
- ⚡ **API RESTful** - Comunicación eficiente backend-frontend
- 🎨 **UI/UX Moderna** - Interfaz intuitiva y atractiva
- 🔄 **Real-time Updates** - Actualizaciones en tiempo real
- 📊 **Dashboard Dinámico** - Métricas y visualizaciones interactivas

## 🏗️ Arquitectura del Sistema

```
┌──────────────────────────────────────────────────────┐
│                    FRONTEND (Angular)                │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │ Components  │  │  Services   │  │   Guards    │   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │   Routing   │  │ HTTP Client │  │ Interceptors│   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
└─────────────────────┬────────────────────────────────┘
                      │ HTTP Requests (JSON)
                      │
┌─────────────────────▼────────────────────────────────┐
│                  BACKEND (Laravel)                   │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │ Controllers │  │   Models    │  │ Migrations  │   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│  │ Middleware  │  │   Routes    │  │   Policies  │   │
│  └─────────────┘  └─────────────┘  └─────────────┘   │
└─────────────────────┬────────────────────────────────┘
                      │
                      ▼
                 ┌─────────────┐
                 │   Database  │
                 │  (MySQL)    │
                 └─────────────┘
```

## 🛠️ Stack Tecnológico

### Backend (Laravel)
- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Base de datos**: MySQL 8.0+
- **Autenticación**: Laravel Sanctum / JWT
- **Validación**: Form Requests
- **ORM**: Eloquent

### Frontend (Angular)
- **Framework**: Angular 17+
- **Lenguaje**: TypeScript 5.x
- **UI Framework**: Angular Material / Bootstrap
- **HTTP Client**: HttpClient con interceptors
- **Estado**: RxJS + Services
- **Routing**: Angular Router con guards

### Herramientas de Desarrollo
- **Control de versiones**: Git
- **Dependency Manager**: Composer (PHP) + npm (Node.js)
- **Build Tools**: Vite (Laravel) + Angular CLI
- **API Testing**: Postman / Insomnia
- **Database Management**: phpMyAdmin / MySQL Workbench

## 📦 Instalación y Configuración

### Prerrequisitos

- PHP 8.2+
- Composer
- Node.js 18+
- npm / yarn
- MySQL 8.0+
- Git

### 🔧 Setup Backend (Laravel)

```bash
# Clonar el repositorio
git clone https://github.com/username/proyecto-laravel-angular.git
cd proyecto-laravel-angular

# Instalar dependencias de PHP
composer install

# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (opcional)
php artisan db:seed

# Configurar JWT/Sanctum
php artisan sanctum:install
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Iniciar servidor de desarrollo
php artisan serve
```

### 🔧 Setup Frontend (Angular)

```bash
# Navegar al directorio frontend
cd frontend

# Instalar dependencias de Node.js
npm install

# Configurar environment (opcional)
# Editar src/environments/environment.ts
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api'
};

# Iniciar servidor de desarrollo
ng serve

# O con configuración específica
ng serve --host 0.0.0.0 --port 4200
```

### 🗄️ Configuración de Base de Datos

```sql
-- Crear base de datos
CREATE DATABASE proyecto_spa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario (opcional)
CREATE USER 'spa_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON proyecto_spa.* TO 'spa_user'@'localhost';
FLUSH PRIVILEGES;
```

## 🚀 Uso y Funcionalidades

### 📱 Características del Frontend

- **Dashboard interactivo** con métricas en tiempo real
- **Gestión de usuarios** con roles y permisos
- **CRUD dinámico** para todas las entidades
- **Filtros y búsquedas** avanzadas
- **Paginación** inteligente
- **Notificaciones** toast y alertas
- **Modo oscuro/claro** toggle
- **Exportación** de datos (PDF, Excel)

### 🔗 Endpoints de la API

```bash
# Autenticación
POST   /api/login
POST   /api/register
POST   /api/logout
GET    /api/user

# Usuarios
GET    /api/users
POST   /api/users
GET    /api/users/{id}
PUT    /api/users/{id}
DELETE /api/users/{id}

# Productos (ejemplo)
GET    /api/products
POST   /api/products
GET    /api/products/{id}
PUT    /api/products/{id}
DELETE /api/products/{id}

# Dashboard
GET    /api/dashboard/stats
GET    /api/dashboard/charts
```

### 🎨 Estructura de Componentes Angular

```
src/app/
├── core/                    # Servicios principales
│   ├── services/
│   ├── guards/
│   ├── interceptors/
│   └── models/
├── shared/                  # Componentes compartidos
│   ├── components/
│   ├── directives/
│   └── pipes/
├── features/                # Módulos de funcionalidades
│   ├── auth/
│   ├── dashboard/
│   ├── users/
│   └── products/
└── layout/                  # Layout de la aplicación
    ├── header/
    ├── sidebar/
    └── footer/
```

## 📊 Ejemplo de Flujo SPA

### 1. Carga Inicial
```typescript
// main.ts
bootstrapApplication(AppComponent, {
  providers: [
    provideRouter(routes),
    provideHttpClient(withInterceptors([authInterceptor])),
    // ... otros providers
  ]
});
```

### 2. Servicio de API
```typescript
// user.service.ts
@Injectable({ providedIn: 'root' })
export class UserService {
  private apiUrl = environment.apiUrl + '/users';

  constructor(private http: HttpClient) {}

  getUsers(page: number = 1): Observable<ApiResponse<User[]>> {
    return this.http.get<ApiResponse<User[]>>(`${this.apiUrl}?page=${page}`);
  }

  createUser(user: CreateUserDto): Observable<User> {
    return this.http.post<User>(this.apiUrl, user);
  }
}
```

### 3. Componente Dinámico
```typescript
// users.component.ts
@Component({
  selector: 'app-users',
  template: `
    <div class="users-container">
      <app-user-list 
        [users]="users$ | async" 
        [loading]="loading$ | async"
        (onEdit)="editUser($event)"
        (onDelete)="deleteUser($event)">
      </app-user-list>
    </div>
  `
})
export class UsersComponent implements OnInit {
  users$ = this.userService.getUsers();
  loading$ = new BehaviorSubject<boolean>(false);

  constructor(private userService: UserService) {}
  
  // Métodos dinámicos sin recarga de página
  editUser(user: User) { /* lógica de edición */ }
  deleteUser(id: number) { /* lógica de eliminación */ }
}
```

## 🔐 Autenticación y Seguridad

### Laravel Sanctum Setup
```php
// config/sanctum.php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    Sanctum::currentApplicationUrlWithPort()
))),
```

### Angular Auth Interceptor
```typescript
export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const token = localStorage.getItem('auth_token');
  
  if (token) {
    req = req.clone({
      setHeaders: { Authorization: `Bearer ${token}` }
    });
  }
  
  return next(req);
};
```

## 📈 Performance y Optimización

- **Lazy Loading** de módulos Angular
- **OnPush** change detection strategy
- **Virtual Scrolling** para listas grandes
- **HTTP Caching** con interceptors
- **Database indexing** en Laravel
- **Query optimization** con Eloquent
- **Image optimization** y lazy loading

## 🧪 Testing

### Backend Testing
```bash
# Tests unitarios
php artisan test

# Tests con coverage
php artisan test --coverage
```

### Frontend Testing
```bash
# Tests unitarios
ng test

# Tests e2e
ng e2e

# Coverage report
ng test --code-coverage
```

## 🚀 Deployment

### Backend (Laravel)
```bash
# Optimización para producción
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend (Angular)
```bash
# Build para producción
ng build --configuration production

# Con análisis de bundle
ng build --stats-json
npx webpack-bundle-analyzer dist/stats.json
```
```
rm -Resource -Force .git
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## 👨‍💻 Autor

**José Daniel Grijalba Osorio**
- Email: jose.grijalba04@gmail.com
- LinkedIn: [LinkedIn](https://www.linkedin.com/in/jose-daniel-go)
- GitHub: [GitHub](https://github.com/Jose-Daniel-G) 

---

⭐ **¡Si este proyecto te fue útil, no olvides darle una estrella!** ⭐