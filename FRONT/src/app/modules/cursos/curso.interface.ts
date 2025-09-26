import { User } from '../../core/models/user.interface';

export interface Curso {
    id: number;
    nombre: string;
    descripcion: string;
    horas_requeridas: number;
    estado: string;
}
export interface PaginatedCursos {
    current_page: number;
    data: Curso[];
    per_page: number;
    last_page: number;
    total: number;
}
export interface CursosResponse {
    cursos: PaginatedCursos;
}