import {User} from "@/types/index";
import {Attachment} from "@/types/attachment";
import {Comment} from "@/types/comment";
import {Group} from "@/types/group";

export interface Post {
    id?: number;
    body?: string;
    user ?: User;
    user_id: number;
    updated_at: string;
    num_of_reactions: number;
    num_of_comments: number;
    user_has_reacted: boolean;
    comments: Comment[];
    group?: Group;
    attachments: Attachment[];
    can: {
        update: boolean;
        delete: boolean;
    };
}
