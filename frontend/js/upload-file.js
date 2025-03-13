const previewPhoto = () => {
    const file = input.files;
    if (file) {
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
    $("body").css("background-color", "rgb(128, 128, 128)");
    $("html").css("background-color", "rgb(128, 128, 128)");
}

$(document).ready(function () {
    if (localStorage.getItem('theme') === 'dark') {
        dark_theme();
    } else {
        $('button').removeClass('dark-button');
    }
});

let input = document.getElementById('file-upload');
input.addEventListener('change', previewPhoto);