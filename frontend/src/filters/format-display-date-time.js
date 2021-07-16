import dayjs from 'dayjs';
import { isEmpty } from 'lodash-es';

const timeZones = {
  HST: 10,
  AKST: 9,
  PST: 8,
  MST: 7,
  CST: 6,
  EST: 5,
};

const estBasedTimeZones = {
  HST: 7,
  AST: 4,
  PST: 3,
  MST: 2,
  CST: 1,
  EST: 0,
};

export default function convertDate(date, timeZone, estBased = false) {
  let result = dayjs(date);

  if (!isEmpty(timeZone)) {
    if (estBased != false) {
      result = result.subtract(estBasedTimeZones[timeZone], 'hour');
    } else {
      result = result.subtract(timeZones[timeZone], 'hour');
    }
  }

  return result.format('MMM DD, YYYY, h:mm a');
}
