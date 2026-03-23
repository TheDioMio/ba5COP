// KPI modal highlight (ativo enquanto modal está aberto)
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.kpi-clickable').forEach(card => {
        const target = card.getAttribute('data-bs-target');
        const modal = document.querySelector(target);

        if (!modal) return;

        modal.addEventListener('show.bs.modal', () => {
            card.classList.add('is-active');
        });

        modal.addEventListener('hidden.bs.modal', () => {
            card.classList.remove('is-active');
        });
    });
});