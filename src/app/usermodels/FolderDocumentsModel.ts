import { Documents, Folders } from "../model";

export class FolderDocumentsViewModel {

		public folders : Folders[]=[];
		public documents : Documents[]=[];
}

export class FolderDocumentsModel extends Folders {

    public folders : Folders[]=[];
    public documents : Documents[]=[];

}