import { setLocalStorageTheme, setButtonSwitchTheme } from "./theme.mjs";

$(document).ready(function () {
  setLocalStorageTheme();
  setButtonSwitchTheme();
});
