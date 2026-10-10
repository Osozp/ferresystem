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

