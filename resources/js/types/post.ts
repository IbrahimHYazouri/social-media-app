import {User} from "@/types/index";
import {Attachment} from "@/types/attachment";

export interface Post {
    id?: number;
    body?: string;
    user ?: User;
    user_id: number;
    updated_at: string;
    attachments: Attachment[]
}
