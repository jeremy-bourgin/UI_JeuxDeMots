import { Pipe, PipeTransform } from '@angular/core';

@Pipe({ name: 'sortBy' })
export class SortByPipe implements PipeTransform {

  transform(value: any[], order = 'asc', column: string = ''): any[] {
    if (!value || order === '' || !order) { return value; } // no array
    if (!column || column === '') { return _.sortBy(value); } // sort 1d array
    if (value.length <= 1) { return value; } // array with only one item

    function sort(a: any, b: any): number
    {
      var temp_a : string = a[column].toString();
      var temp_b : string = b[column].toString();

      var options: any = {
        sensitivity: 'base',
        numeric: false
      }

      if (!isNaN(a[column]) && !isNaN(b[column]))
      {
        options.numeric = true;
      }

      return temp_a.localeCompare(temp_b, 'fr', options);
    }

    var r: any = Array.from(value).sort(sort);

    if (order === 'desc')
    {
      r.reverse();
    }

    return r;
  }
}