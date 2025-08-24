// Fichier JavaScript principal pour l'application Innovation Hub

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des fonctionnalités
    initializeAlerts();
    initializeTooltips();
    initializeAnimations();
    initializeFormEnhancements();
});

// Gestion des alertes avec auto-fermeture
function initializeAlerts() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Ajouter un bouton de fermeture
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.className = 'alert-close';
        closeBtn.style.cssText = `
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            margin-left: auto;
            padding: 0;
            font-size: 1rem;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        `;
        
        closeBtn.addEventListener('click', () => {
            alert.style.animation = 'slideUp 0.3s ease forwards';
            setTimeout(() => alert.remove(), 300);
        });
        
        closeBtn.addEventListener('mouseenter', () => {
            closeBtn.style.opacity = '1';
        });
        
        closeBtn.addEventListener('mouseleave', () => {
            closeBtn.style.opacity = '0.7';
        });
        
        alert.appendChild(closeBtn);
        
        // Auto-fermeture après 5 secondes pour les alertes de succès
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.style.animation = 'slideUp 0.3s ease forwards';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }
    });
}

// Initialisation des tooltips
function initializeTooltips() {
    const elementsWithTooltip = document.querySelectorAll('[data-tooltip]');
    
    elementsWithTooltip.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(e) {
    const element = e.target;
    const tooltipText = element.getAttribute('data-tooltip');
    
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = tooltipText;
    tooltip.style.cssText = `
        position: absolute;
        background: #1f2937;
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        z-index: 1000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s ease;
        white-space: nowrap;
    `;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    
    setTimeout(() => tooltip.style.opacity = '1', 10);
    
    element._tooltip = tooltip;
}

function hideTooltip(e) {
    const element = e.target;
    if (element._tooltip) {
        element._tooltip.style.opacity = '0';
        setTimeout(() => {
            if (element._tooltip && element._tooltip.parentNode) {
                element._tooltip.parentNode.removeChild(element._tooltip);
            }
            delete element._tooltip;
        }, 200);
    }
}

// Animations d'entrée pour les éléments
function initializeAnimations() {
    const animatedElements = document.querySelectorAll('.card, .idea-card, .evaluation-card, .top-idea-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.animationDelay = `${index * 0.1}s`;
        observer.observe(element);
    });
}

// Améliorations des formulaires
function initializeFormEnhancements() {
    // Compteur de caractères pour les textarea
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        if (textarea.hasAttribute('maxlength')) {
            addCharacterCounter(textarea);
        }
    });
    
    // Validation en temps réel
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
    
    // Amélioration des sélecteurs de fichiers
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(enhanceFileInput);
}

function addCharacterCounter(textarea) {
    const maxLength = textarea.getAttribute('maxlength');
    const counter = document.createElement('div');
    counter.className = 'character-counter';
    counter.style.cssText = `
        text-align: right;
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    `;
    
    const updateCounter = () => {
        const remaining = maxLength - textarea.value.length;
        counter.textContent = `${textarea.value.length}/${maxLength}`;
        
        if (remaining < 50) {
            counter.style.color = '#ef4444';
        } else if (remaining < 100) {
            counter.style.color = '#f59e0b';
        } else {
            counter.style.color = '#6b7280';
        }
    };
    
    textarea.addEventListener('input', updateCounter);
    textarea.parentNode.appendChild(counter);
    updateCounter();
}

function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    
    // Validation email
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Format d\'email invalide.');
            return;
        }
    }
    
    // Validation champs requis
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Ce champ est requis.');
        return;
    }
    
    // Validation longueur minimale
    if (field.hasAttribute('minlength')) {
        const minLength = parseInt(field.getAttribute('minlength'));
        if (value.length < minLength) {
            showFieldError(field, `Minimum ${minLength} caractères requis.`);
            return;
        }
    }
    
    clearFieldError(field);
}

function showFieldError(field, message) {
    field.classList.add('error');
    const errorElement = field.parentNode.querySelector('.error-text');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function clearFieldError(e) {
    const field = e.target || e;
    field.classList.remove('error');
    const errorElement = field.parentNode.querySelector('.error-text');
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
}

function enhanceFileInput(input) {
    const wrapper = document.createElement('div');
    wrapper.className = 'file-input-wrapper';
    wrapper.style.cssText = `
        position: relative;
        display: inline-block;
        cursor: pointer;
    `;
    
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-secondary';
    button.innerHTML = '<i class="fas fa-upload"></i> Choisir un fichier';
    
    const label = document.createElement('span');
    label.className = 'file-label';
    label.textContent = 'Aucun fichier sélectionné';
    label.style.cssText = `
        margin-left: 1rem;
        color: #6b7280;
        font-size: 0.875rem;
    `;
    
    input.style.display = 'none';
    
    button.addEventListener('click', () => input.click());
    
    input.addEventListener('change', () => {
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
            label.style.color = '#059669';
        } else {
            label.textContent = 'Aucun fichier sélectionné';
            label.style.color = '#6b7280';
        }
    });
    
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);
    wrapper.appendChild(button);
    wrapper.appendChild(label);
}

// Fonctions utilitaires
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
            max-height: 100px;
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
            max-height: 0;
            padding: 0;
            margin: 0;
        }
    }
    
    .alert-close:hover {
        transform: scale(1.1);
    }
`;
document.head.appendChild(style);

