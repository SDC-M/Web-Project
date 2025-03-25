import { setFileUploadPreview } from "./data-treatment.mjs";
import { setLocalStorageTheme } from "./theme.mjs";

$(document).ready(function () {
    setLocalStorageTheme();
    setFileUploadPreview();
});
