import {User} from "@/types/index";

export interface Comment {
    id: number;
    post_id: number;
    user_id: number;
    comment: string;
    updated_at: string;
    num_of_reactions: number;
    user_has_reacted: boolean;
    replies?: Comment[],
    num_of_replies?: number;
    depth: number;
    user?: User;
    can: {
        update: boolean;
        delete: boolean;
    };
}
