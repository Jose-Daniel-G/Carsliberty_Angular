import { Routes } from '@angular/router';

export default [
  {
    path: '',
    loadComponent: () => import('./index/index.component').then(m => m.IndexComponent), // lista de clientes
  },
  {
    path: 'create',
    loadComponent: () => import('./create/create.component').then(m => m.CreateComponent),
  },
  {
    path: 'edit/:id',
    loadComponent: () => import('./create/create.component').then(m => m.CreateComponent),
  },
] as Routes;
