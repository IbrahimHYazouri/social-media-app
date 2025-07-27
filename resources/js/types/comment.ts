import {User} from "@/types/index";

export interface Comment {
    id: number;
    post_id: number;
    user_id: number;
    comment: string;
    updated_at: string;
    user: User;
}
