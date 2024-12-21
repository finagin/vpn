export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    created_at: string;
    updated_at: string;

    outlines: Outline[];
}

export interface Outline {
    id: number;
    name: string;
    url: string;
    limit: number;
    spending: number;

    user?: User;
}
