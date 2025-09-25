import { Component, OnInit } from '@angular/core';
import { HeaderComponent } from '../../shared/header/header.component';
import { FooterComponent } from '../../shared/footer/footer.component';
import { RouterModule } from '@angular/router';
declare var $: any;
declare function initPageEcommerce([]): any;
// declare function initPageEcommerce($: any): any; // Corrected function name
@Component({
  selector: 'app-auth-profile',
  standalone: true,
  imports: [RouterModule,HeaderComponent, FooterComponent,],
  templateUrl: './auth-profile.component.html',
  styleUrl: './auth-profile.component.css'
})
export class AuthProfileComponent implements OnInit{
  constructor() {}

  ngOnInit(): void {
    setTimeout(()=>{
      initPageEcommerce($);
    },50);
  }
}
