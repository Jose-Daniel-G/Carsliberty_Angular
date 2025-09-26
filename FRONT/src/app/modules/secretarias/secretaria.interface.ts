import { User } from '../../core/models/user.interface';

export interface Secretaria {
    id: number;
    nombres: string;
    apellidos: string;
    cc: number;
    celular: number;
    fecha_nacimiento: Date;
    direccion: string;
    user: User;  // <- agregado
}
export interface PaginatedSecretarias {
    current_page: number;
    data: Secretaria[];
    per_page: number;
    last_page: number;
    total: number;
    // puedes agregar otros campos si los necesitas
}
export interface SecretariasResponse {
    secretarias: PaginatedSecretarias;
}