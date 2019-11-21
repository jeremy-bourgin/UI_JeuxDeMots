import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-logo',
  templateUrl: './logo.component.html',
  styleUrls: ['./logo.component.css'],
  host: {'class': 'logo-index'}
})
export class LogoComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }

}
