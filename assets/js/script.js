// JavaScript personnalisé pour RestoCampus

// Fonction pour confirmer la suppression
function confirmDelete(message = "Êtes-vous sûr de vouloir supprimer cet élément ?") {
    return confirm(message);
}

// Fonction pour afficher les messages d'alerte temporaires
function showAlert(message, type = 'success', duration = 5000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    // Auto-suppression après la durée spécifiée
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, duration);
}

// Fonction pour valider les formulaires
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Fonction pour gérer les quantités dans les commandes
function updateQuantity(button, change) {
    const input = button.parentNode.querySelector('input[type="number"]');
    const currentValue = parseInt(input.value) || 0;
    const newValue = Math.max(1, currentValue + change);
    input.value = newValue;
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons de suppression
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm-delete') || "Êtes-vous sûr de vouloir supprimer cet élément ?";
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Gestion de la validation des formulaires
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this.id)) {
                e.preventDefault();
                showAlert('Veuillez remplir tous les champs obligatoires.', 'danger');
            }
        });
    });

    // Animation des cartes au survol
    const cards = document.querySelectorAll('.plat-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Auto-dismiss des alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (!alert.querySelector('.btn-close')) return;

        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Fonction utilitaire pour formater les dates
function formatDate(dateString) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

// Fonction pour gérer les tooltips Bootstrap
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}
