import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { SpeedDialOptionModel } from 'src/app/model';

@Component({
  selector: 'app-speed-dial',
  templateUrl: './speed-dial.component.html',
  styleUrls: ['./speed-dial.component.scss']
})
export class SpeedDialComponent implements OnInit {
  @Input() mainButtonText!: string;
  @Input() options!: SpeedDialOptionModel[];
  @Input() direction: 'up' | 'down' | 'left' | 'right' = 'up'; // Default direction is 'up'
  isOpen: boolean = false;
  rotate: string = "";
  @Output() onOptionClicked = new EventEmitter<string>();

  constructor() { }

  ngOnInit(): void {
  }
  
  toggleSpeedDial() {
    this.isOpen = !this.isOpen;
  }

  onOptionClick(option: SpeedDialOptionModel) {
    console.log('Selected option:', option);
    this.onOptionClicked.emit(option.contentType);
    // Handle the click event for each option (e.g., emit an event or perform an action)
  }
  mouseenter(){
    this.isOpen = true;
    this.rotate = "fa-rotate-by";
  }
  mouseleave(){
    this.isOpen = false;
    this.rotate = "";
  }
}
