import { Routes, RouterModule } from '@angular/router';
import { DashboardComponent } from './index/dashboard.component';


const routes: Routes = [
  { path: '', component: DashboardComponent } // 👈 Importante que el path esté vacío
];

export default RouterModule.forChild(routes);
