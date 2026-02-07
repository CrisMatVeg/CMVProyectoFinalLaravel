const ProyectoModal = (() => {
    // Elementos del DOM
    const modalOverlay = document.querySelector('.modal-overlay');
    const openBtn = document.querySelector('.create-project-btn');
    const cancelBtn = document.querySelector('.cancel-btn');

    // Función para abrir el modal
    const openModal = () => {
        modalOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    const closeModal = () => {
        modalOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Inicializa eventos
    const init = () => {
        if (!openBtn || !cancelBtn || !modalOverlay) return;

        openBtn.addEventListener('click', openModal);
        cancelBtn.addEventListener('click', closeModal);

        // Si quiero que el modal se cierre al hacer click fuera de él:
        // modalOverlay.addEventListener('click', (e) => {
        //     if (e.target === modalOverlay) closeModal();
        // });
    };

    return {
        init
    };
})();

document.addEventListener('DOMContentLoaded', () => {
    ProyectoModal.init();
});
