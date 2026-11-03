import "@tailwindplus/elements";

import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
window.Swal = Swal;

document.querySelectorAll(".delete-form").forEach((form) => {
    if (!form.dataset.initialized) {
        form.dataset.initialized = "true";
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            if (confirm("Estas Seguro que deseas eliminar")) {
                form.submit();
            }
        });
    }
});

if (document.getElementById("register-form")) {
    import("./modules/register.js");
}

const themeToggleBtn = document.getElementById('theme-toggle');
const darkIcon = document.getElementById('theme-toggle-dark-icon');
const lightIcon = document.getElementById('theme-toggle-light-icon');

// Determinar tema inicial
if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
    lightIcon.classList.remove('hidden');
} else {
    document.documentElement.classList.remove('dark');
    darkIcon.classList.remove('hidden');
}

// Evento click para alternar
themeToggleBtn.addEventListener('click', () => {
    darkIcon.classList.toggle('hidden');
    lightIcon.classList.toggle('hidden');

    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
});
