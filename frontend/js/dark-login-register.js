import { setHelpValidator, setLocalStorageTheme } from "./theme.mjs";

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
    setLocalStorageTheme();
    setHelpValidator();
});