
export class constants {
    public static ObjectStorageKey = "StorageKey";
    public static ContentImageUrlFormat = "ContentImageUrlFormat";
    public static AllowedTotalContentSize = "AllowedTotalContentSize"
    public static VarcharList = ["binary", "varbinary", "tinyblob", "blob", "mediumblob", "longblob", "char byte", "char", "varchar", "tinytext", "text", "mediumtext", "longtext", "set", "enum", "nchar", "national char", "nvarchar", "national varchar", "character varying"]
    public static IntList = ["number", "int", "integer", "smallint unsigned", "mediumint", "bigint", "int unsigned", "integer unsigned", "bit"]
    public static TinyintList = ["tinyint"]
    public static documentSave = "This document will automatically save as you make changes.";

    public static convertStringToNNumber(value: string) {
        return Number(value);
    }

}