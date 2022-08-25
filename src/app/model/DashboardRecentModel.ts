import { MentionsModel } from "./MentionsModel";

export class DashboardRecentModel extends MentionsModel{
    public user_id? : number;
    public updated_at? : string;
    public icon? : string;
    public primary_color? : string;
    public timeSince?:string;
}