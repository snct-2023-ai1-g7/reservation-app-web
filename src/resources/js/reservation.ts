export class Reservation {
  start: Date;
  startStr: string;
  end: Date;
  endStr: string;
  room_number: Number;

  constructor(start: string, end: string, room_number: Number) {
    const startComponents = start.split(":"); 
    
    const hoursStart = parseInt(startComponents[0]);
    const minutesStart = parseInt(startComponents[1]);
    this.start = new Date();
    this.start.setHours(hoursStart);
    this.start.setMinutes(minutesStart);
    this.start.setSeconds(0);
    this.start.setMilliseconds(0);
    this.startStr = `${this.start.getHours()}:${this.start.getMinutes()<10?'0':''}${this.start.getMinutes()}`;

    const endComponents = end.split(":");
    const hoursEnd = parseInt(endComponents[0]);
    const minutesEnd = parseInt(endComponents[1]);
    this.end = new Date();
    this.end.setHours(hoursEnd);
    this.end.setMinutes(minutesEnd);
    this.end.setSeconds(0);
    this.end.setMilliseconds(0);
    this.endStr = `${this.end.getHours()}:${this.end.getMinutes()<10?'0':''}${this.end.getMinutes()}`;

    this.room_number = room_number;
  }
}