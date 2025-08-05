export interface Notification {
    id: string;
    type: string;
    data: Record<string, any>;
    created_at: string
}
