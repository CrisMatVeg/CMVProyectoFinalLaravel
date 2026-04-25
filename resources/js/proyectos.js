const ProyectoModal = (() => {
    // Elementos del DOM
    const modalOverlay = document.querySelector(".modal-overlay");
    const openBtn = document.querySelector(".create-project-btn");
    const cancelBtn = document.querySelector(".cancel-btn");

    // Función para abrir el modal
    const openModal = () => {
        modalOverlay.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    };

    const closeModal = () => {
        modalOverlay.classList.add("hidden");
        document.body.style.overflow = "";
    };

    // Inicializa eventos
    const init = () => {
        if (!openBtn || !cancelBtn || !modalOverlay) return;

        openBtn.addEventListener("click", openModal);
        cancelBtn.addEventListener("click", closeModal);
    };

    return {
        init,
    };
})();

document.addEventListener("DOMContentLoaded", () => {
    ProyectoModal.init();
});

// PANEL DE OPCIONES
const allOptions = document.querySelectorAll(".project-options");

allOptions.forEach((option) => {
    const menu = option.querySelector(".options-menu");

    option.addEventListener("click", (e) => {
        e.stopPropagation();

        // Cerrar todos los demás paneles primero
        document.querySelectorAll(".options-menu").forEach((m) => {
            if (m !== menu) m.classList.add("hidden");
        });

        // Toggle del menú actual
        menu.classList.toggle("hidden");
    });
});

// Cerrar paneles al hacer click fuera
document.addEventListener("click", () => {
    document
        .querySelectorAll(".options-menu")
        .forEach((menu) => menu.classList.add("hidden"));
});

// MODALES
const editModal = document.getElementById("edit-modal");
const deleteModal = document.getElementById("delete-modal");

const editForm = document.getElementById("edit-form");
const deleteForm = document.getElementById("delete-form");

// Abrir modal editar
document.querySelectorAll(".open-edit-modal").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();

        const projectId = e.currentTarget.dataset.projectId;

        // Aquí puedes rellenar los campos con datos del proyecto
        const card = document.querySelector(
            `.main-card[data-project-id="${projectId}"]`,
        );
        editForm.action = `/proyectos/${projectId}`; // Ajusta según tu ruta
        editForm.querySelector("#edit-name").value =
            card.querySelector("h3").innerText;
        editForm.querySelector("#edit-desc").value =
            card.querySelector("span").innerText;

        editModal.classList.remove("hidden");
    });
});

// Abrir modal eliminar
document.querySelectorAll(".open-delete-modal").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();

        const projectId = e.currentTarget.dataset.projectId;
        deleteForm.action = `/proyectos/${projectId}`; // Ajusta según tu ruta
        deleteModal.classList.remove("hidden");
    });
});

// Cerrar modales
document.querySelectorAll(".cancel-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const modalId = btn.dataset.modal;
        document.getElementById(modalId).classList.add("hidden");
    });
});

// Cerrar modales al click fuera del modal
document.querySelectorAll(".modal-overlay").forEach((modal) => {
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });
});

// Modal Unirse a proyecto
const joinModal = document.getElementById("join-modal");
document.querySelector(".join-project-btn")?.addEventListener("click", () => {
    joinModal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
});
