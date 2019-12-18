import { Pipe, PipeTransform } from '@angular/core';

@Pipe({ name: 'sortBy' })
export class SortByPipe implements PipeTransform {

  transform(value: any[], order = 'asc', column: string = ''): any[] {
    if (!value) { return value; } // no array
    if (value.length <= 1) { return value; } // array with only one item

    function get_value(e: any): any
    {
      if (!column || column === '')
      {
        return e;
      }

      return e[column];
    }

    function sort(a: any, b: any): number
    {
      var temp_a : any = get_value(a);
      var temp_b : any = get_value(b);

      var temp_a_string : string = temp_a.toString();
      var temp_b_string : string = temp_b.toString();

      var options: any = {
        sensitivity: 'base',
        numeric: false
      }

      if (!isNaN(temp_a) && !isNaN(temp_b))
      {
        options.numeric = true;
      }

      return temp_a_string.localeCompare(temp_b_string, 'fr', options);
    }

    var r: any = Array.from(value).sort(sort);

    if (order === 'desc')
    {
      r.reverse();
    }

    return r;
  }
}