import {email, object, string} from "zod";

export const userFormSchema = object({
    name: string().min(1, "Full name is required"),
    email: string().min(1, "Email is required").email("Invalid email")
})