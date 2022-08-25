import { Component, OnInit, HostBinding, Input, SimpleChanges} from '@angular/core';
import { DomSanitizer, SafeHtml } from "@angular/platform-browser";

@Component({
  selector: 'app-sidebar-nav',
  templateUrl: './sidebar-nav.component.html',
  styleUrls: ['./sidebar-nav.component.css']
})
export class SidebarNavComponent implements OnInit {

    @Input() navItems: Array<any> = new Array<any>();
    @HostBinding('class.sidebar-nav') true: any;
    @HostBinding('attr.role') role: any;

    constructor() {
      console.log(this.navItems);
    }

    isDivider(navItem: { divider: any; }) {
        return !!navItem.divider
    }

    isTitle(navItem: { title: any; }) {
        return !!navItem.title
    }

    isHasChild(navItem: { hasOwnProperty: (arg0: string) => any; children: string | any[]; }) {
        return navItem.hasOwnProperty('children') && navItem.children.length > 0;
    } 
    
    ngOnChanges(changes: SimpleChanges) {
      console.log(changes);
      for (let propName in changes) {  
        let change = changes[propName];
        let curVal  = JSON.stringify(change.currentValue);
        let prevVal = JSON.stringify(change.previousValue);
      
              console.log(curVal);
              console.log(prevVal);
           }
    }
  ngOnInit(): void {
  }

}
