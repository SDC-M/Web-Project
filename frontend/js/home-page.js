import { setLocalStorageTheme, setButtonSwitchTheme } from "./theme.mjs";

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/**
 * Anime le caroussel.
 */
function rotateSlides() {
  var $firstSlide = $("#carousel").find("div:first");
  var width = $firstSlide.width();

  $firstSlide.animate({ marginLeft: -width }, 1000, function () {
    var $lastSlide = $("#carousel").find("div:last");
    $lastSlide.after($firstSlide);
    $firstSlide.css({ marginLeft: 0 });
  });
}

/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

$(document).ready(function () {
  window.setInterval(rotateSlides, 3000);
  setLocalStorageTheme();
  setButtonSwitchTheme();
});
