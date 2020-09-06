/* eslint-disable no-console */
const { name: packageName } = require('../../../package.json');

const clearAndUpper = (text) => text.replace(/-/, '').toUpperCase();
const toPascalCase = (text) => text.replace(/(^\w|-\w)/g, clearAndUpper);

const formattedPackageName = toPascalCase(packageName);

/**
 * Method used to debug info to console
 * @param     {string | object }   message    The message or object to log
 * @return    {void}
 */
export const debug = (message) => {
  console.debug(`${formattedPackageName} - ${message}`);
};

/**
 * Method used to log errors to console
 * @param     {string | object}    message    The message or object to log
 * @return    {void}
 * @instance
 */
export const error = (message) => {
  console.error(`${formattedPackageName} - ${message}`);
};

/**
 * Method used to log warnings to console
 * @param     {string | object}    message    The message or object to log
 * @return    {void}
 * @instance
 */
export const warn = (message) => {
  console.warn(`${formattedPackageName} - ${message}`);
};
