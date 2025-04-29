document.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("darkMode") === "enabled") {
        document.body.classList.add("dark-mode");
        const darkModeToggleIcon = document.querySelector('#darkModeToggle i');
        if (darkModeToggleIcon) {
            darkModeToggleIcon.classList.remove('fa-moon');
            darkModeToggleIcon.classList.add('fa-sun');
        }
    }
});

const darkModeToggle = document.getElementById("darkModeToggle");
if (darkModeToggle) {
    darkModeToggle.addEventListener("click", function () {
        const isDarkMode = document.body.classList.toggle("dark-mode");
        const darkModeToggleIcon = document.querySelector('#darkModeToggle i');

        if (isDarkMode) {
            localStorage.setItem("darkMode", "enabled");
            document.body.style.backgroundColor = "#121212";
            if (darkModeToggleIcon) {
                darkModeToggleIcon.classList.remove('fa-moon');
                darkModeToggleIcon.classList.add('fa-sun');
            }
        } else {
            localStorage.setItem("darkMode", "disabled");
            document.body.style.backgroundColor = "#87bae9";
            if (darkModeToggleIcon) {
                darkModeToggleIcon.classList.remove('fa-sun');
                darkModeToggleIcon.classList.add('fa-moon');
            }
        }
    });
}