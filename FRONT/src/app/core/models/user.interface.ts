export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
  two_factor_confirmed_at: string | null;
  current_team_id: number | null;
  profile_photo_path: string | null;
  status: number;
  created_at: string;
  updated_at: string;
  profile_photo_url: string;
}