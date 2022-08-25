import { DashboardRecentModel } from "./DashboardRecentModel";

export class DashboardItem extends DashboardRecentModel {
    public Header?: string;
    public CountString?: string;
    public ItemKey?: string;
    public Controller?: string;
    public Action?: string;
    public Color?: string;
}