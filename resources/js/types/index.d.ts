import { Config } from 'ziggy-js';

export interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    email_verified_at?: string;
    password ?: string;
    cover_url ?: string;
    avatar_url ?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};
