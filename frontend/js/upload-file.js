const previewPhoto = () => {
    const file = $('#file-upload')[0].files;
    if (file && file[0]) {
        const fileReader = new FileReader();
        const preview = $('#file-preview');
        fileReader.onload = function (event) {
            preview.attr('src', event.target.result);
        }
        fileReader.readAsDataURL(file[0]);
    }
}

function dark_theme() {
    $("#container").removeClass("light-mode").addClass("dark-mode");
    $("body, html").css("background-color", "rgb(128, 128, 128)");
    localStorage.setItem('theme', 'dark');
}

function light_theme() {
    $("#container").removeClass("dark-mode").addClass("light-mode");
    $("body, html").css("background-color", "white");
    localStorage.setItem('theme', 'light');
}

$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        dark_theme();
    } else {
        light_theme();
    }
    $('#theme-toggle').on('click', function () {
        if (localStorage.getItem('theme') === 'dark') {
            light_theme();
        } else {
            dark_theme();
        }
    });
    $('#file-upload').on('change', previewPhoto);
});
