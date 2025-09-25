import { Component, OnInit } from '@angular/core';
import { SharedComponent } from '../../shared/shared.component';
import { HeaderComponent } from '../../shared/header/header.component';
import { FooterComponent } from '../../shared/footer/footer.component';
declare var $: any;
declare function initPageEcommerce([]): any;

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [SharedComponent, HeaderComponent, FooterComponent],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css',
})
export class HomeComponent implements OnInit{
  constructor() {}

  ngOnInit(): void {
    setTimeout(()=>{
      initPageEcommerce($);
    },50);
  }
}
