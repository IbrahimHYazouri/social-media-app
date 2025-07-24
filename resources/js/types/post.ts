import {User} from "@/types/index";

export interface Post {
    id?: number;
    body?: string;
    user ?: User;
    user_id: number;
    updated_at: string;
}
