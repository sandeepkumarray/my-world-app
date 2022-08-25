
export class ContentTemplateModel {
    public template_name?: string;
    public contents!: Content[];
}

export class Content {
    public content_type?: string;
    public is_public?: boolean;
    public categories!: Category[];
    public references!: Category[];
}

export class Category {
    public order!: number;
    public label?: string;
    public name?: string;
    public icon?: string;
    public attributes!: Attribute[];
    public is_active!: boolean;
    public index!: number;

}

export class Attribute {
    public field_name!: string;
    public field_label?: string;
    public field_type!: string;
    public field_value!: string;
    public help_text?: string;
    public is_active?: boolean;
    public is_hidden?: boolean;
    public is_user_defined?: boolean;
    public allow_null?: boolean;
    public auto_increament?: boolean;
}