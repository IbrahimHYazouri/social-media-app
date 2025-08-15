import { Config } from 'ziggy-js';
import {Notification} from "@/types/notification";

export interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    email_verified_at?: string;
    password ?: string;
    cover_url ?: string;
    avatar_url ?: string;
    status ?: string;
    role ?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
        notifications: {
            unread: Notification[],
            count: number
        }
    };
    allowedAttachmentExtensions: string[];
    ziggy: Config & { location: string };
};
