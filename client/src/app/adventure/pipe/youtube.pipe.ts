import {Pipe, PipeTransform} from "@angular/core";
import {DomSanitizer, SafeResourceUrl} from "@angular/platform-browser";

@Pipe({name: 'youtube'})
export class YoutubePipe implements PipeTransform {
  constructor(private sanitizer: DomSanitizer) {
  }

  transform(value?: string): SafeResourceUrl {
    // @ts-ignore
    return this.sanitizer.bypassSecurityTrustResourceUrl(`https://youtube.com/embed/${value?.match(/v=(.+)$/)[1]}`);
  }
}
