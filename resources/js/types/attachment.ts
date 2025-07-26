export interface Attachment {
    id: number;
    uuid: string;
    name: string;
    file_name: string;
    mime_type: string;
    size: number;
    url: string;
    preview_url: string;
    is_image: boolean;
    human_readable_size: string;
    deleted ?: boolean
}
