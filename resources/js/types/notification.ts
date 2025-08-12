export interface Notification {
    id: string;
    type: string;
    data: {
        message: string;
        target_route: string;
        target_params: Record<string, string>;
    };
    created_at?: string;
    read_at ?: string;
}
