import {DateTime} from "luxon";

/**
 * Receber data no formato ISO e transformar no formato desejado
 *
 * @see https://github.com/moment/luxon/blob/master/docs/formatting.md
 *
 * @param {string} value
 * @param {string} to_format
 * @param {string|null} from_format
 *
 * @return string
 */
export function $dateFormat(value, to_format = 'dd/MM/yyyy', from_format = null) {
    if (!from_format) {
        return DateTime.fromISO(value).toFormat(to_format);
    }

    return DateTime.fromFormat(value.toString(), from_format).toFormat(to_format);
}

/**
 * Receber data no formato ISO e transformar no formato desejado de datahora
 *
 * @see https://github.com/moment/luxon/blob/master/docs/formatting.md
 *
 * @param {string} value
 * @param {string} to_format
 * @param {string|null} from_format
 *
 * @return string
 */
export function $dateTimeFormat(value, to_format = 'dd/MM/yyyy HH:ii', from_format = null) {
    if (!from_format) {
        return DateTime.fromISO(value).toFormat(to_format);
    }

    return DateTime.fromFormat(value.toString(), from_format).toFormat(to_format);
}
