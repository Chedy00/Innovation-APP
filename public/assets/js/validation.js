// Fonctions utilitaires pour la validation côté client

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Ajouter la classe error à l'input correspondant
        const inputId = elementId.replace('-error', '');
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.classList.add('error');
        }
    }
}

function clearErrors() {
    const errorElements = document.querySelectorAll('.error-text');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    const inputElements = document.querySelectorAll('input.error');
    inputElements.forEach(element => {
        element.classList.remove('error');
    });
}

function validateRequired(value, fieldName) {
    if (!value || value.trim() === '') {
        return `Le champ ${fieldName} est requis.`;
    }
    return null;
}

function validateEmail(email) {
    if (!email || email.trim() === '') {
        return 'L\'email est requis.';
    }
    if (!isValidEmail(email)) {
        return 'Format d\'email invalide.';
    }
    return null;
}

function validatePassword(password, minLength = 6) {
    if (!password || password.trim() === '') {
        return 'Le mot de passe est requis.';
    }
    if (password.length < minLength) {
        return `Le mot de passe doit contenir au moins ${minLength} caractères.`;
    }
    return null;
}

function validateSelect(value, fieldName) {
    if (!value || value === '') {
        return `Veuillez sélectionner ${fieldName}.`;
    }
    return null;
}

function validateTextLength(text, maxLength, fieldName) {
    if (text && text.length > maxLength) {
        return `${fieldName} ne peut pas dépasser ${maxLength} caractères.`;
    }
    return null;
}

function validateNote(note) {
    const noteNum = parseInt(note);
    if (isNaN(noteNum) || noteNum < 1 || noteNum > 5) {
        return 'La note doit être comprise entre 1 et 5.';
    }
    return null;
}

// Fonction générique pour valider un formulaire
function validateForm(formId, validationRules) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    clearErrors();
    
    for (const fieldId in validationRules) {
        const field = document.getElementById(fieldId);
        if (!field) continue;
        
        const rules = validationRules[fieldId];
        const value = field.value;
        
        for (const rule of rules) {
            const error = rule(value);
            if (error) {
                showError(fieldId + '-error', error);
                isValid = false;
                break;
            }
        }
    }
    
    return isValid;
}

// Ajouter des écouteurs d'événements pour supprimer les erreurs lors de la saisie
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                const errorElement = document.getElementById(this.id + '-error');
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
            }
        });
    });
});

