export interface Group {
    id: number;
    name: string;
    slug: string;
    auto_approval: boolean;
    about?: string;
    user_id: number;
    role: string;
    status: string;
    avatar_url ?: string;
    cover_url ?: string;
    created_at: string;
    updated_at: string;
    can: {
        manage: boolean,
        participate: boolean,
        join: boolean
    }
}
