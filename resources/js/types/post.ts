import {User} from "@/types/index";

export interface Post {
    id?: number;
    body?: string;
    user ?: User;
    updated_at: string;
}
