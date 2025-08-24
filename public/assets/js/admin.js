// JavaScript spécifique à l'interface d'administration

document.addEventListener('DOMContentLoaded', function() {
    initializeAdminFeatures();
    initializeDataTables();
    initializeConfirmDialogs();
    initializeBulkActions();
});

function initializeAdminFeatures() {
    // Sidebar responsive
    initializeSidebar();
    
    // Statistiques en temps réel
    initializeStatsUpdater();
    
    // Raccourcis clavier
    initializeKeyboardShortcuts();
}

function initializeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    // Toggle sidebar sur mobile
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'sidebar-toggle';
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    toggleBtn.style.cssText = `
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1001;
        background: var(--admin-primary);
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    `;
    
    document.body.appendChild(toggleBtn);
    
    // Afficher le bouton sur mobile
    function checkScreenSize() {
        if (window.innerWidth <= 1024) {
            toggleBtn.style.display = 'block';
            sidebar.classList.add('mobile-hidden');
        } else {
            toggleBtn.style.display = 'none';
            sidebar.classList.remove('mobile-hidden', 'mobile-visible');
        }
    }
    
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-visible');
    });
    
    // Fermer la sidebar en cliquant à l'extérieur
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 1024 && 
            !sidebar.contains(e.target) && 
            !toggleBtn.contains(e.target) &&
            sidebar.classList.contains('mobile-visible')) {
            sidebar.classList.remove('mobile-visible');
        }
    });
    
    window.addEventListener('resize', checkScreenSize);
    checkScreenSize();
}

function initializeDataTables() {
    const tables = document.querySelectorAll('.table');
    
    tables.forEach(table => {
        // Tri des colonnes
        addTableSorting(table);
        
        // Recherche dans le tableau
        addTableSearch(table);
        
        // Pagination si nécessaire
        if (table.rows.length > 20) {
            addTablePagination(table);
        }
    });
}

function addTableSorting(table) {
    const headers = table.querySelectorAll('th');
    
    headers.forEach((header, index) => {
        if (header.textContent.trim() && !header.classList.contains('no-sort')) {
            header.style.cursor = 'pointer';
            header.style.userSelect = 'none';
            header.innerHTML += ' <i class="fas fa-sort sort-icon"></i>';
            
            header.addEventListener('click', () => {
                sortTable(table, index, header);
            });
        }
    });
}

function sortTable(table, columnIndex, header) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.rows);
    const isAscending = !header.classList.contains('sort-asc');
    
    // Reset all sort icons
    table.querySelectorAll('.sort-icon').forEach(icon => {
        icon.className = 'fas fa-sort sort-icon';
    });
    
    // Update current header
    const icon = header.querySelector('.sort-icon');
    icon.className = `fas fa-sort-${isAscending ? 'up' : 'down'} sort-icon`;
    header.classList.toggle('sort-asc', isAscending);
    header.classList.toggle('sort-desc', !isAscending);
    
    rows.sort((a, b) => {
        const aText = a.cells[columnIndex].textContent.trim();
        const bText = b.cells[columnIndex].textContent.trim();
        
        // Essayer de comparer comme des nombres
        const aNum = parseFloat(aText);
        const bNum = parseFloat(bText);
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return isAscending ? aNum - bNum : bNum - aNum;
        }
        
        // Comparer comme du texte
        return isAscending ? 
            aText.localeCompare(bText) : 
            bText.localeCompare(aText);
    });
    
    // Réorganiser les lignes
    rows.forEach(row => tbody.appendChild(row));
}

function addTableSearch(table) {
    const searchContainer = document.createElement('div');
    searchContainer.className = 'table-search';
    searchContainer.style.cssText = `
        margin-bottom: 1rem;
        display: flex;
        justify-content: flex-end;
    `;
    
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Rechercher dans le tableau...';
    searchInput.className = 'form-input';
    searchInput.style.maxWidth = '300px';
    
    searchContainer.appendChild(searchInput);
    table.parentNode.insertBefore(searchContainer, table);
    
    searchInput.addEventListener('input', debounce(() => {
        filterTable(table, searchInput.value);
    }, 300));
}

function filterTable(table, searchTerm) {
    const tbody = table.querySelector('tbody');
    if (!tbody) return;

    const rows = Array.from(tbody.rows); // Use rows collection
    const term = searchTerm.toLowerCase().trim();

    if (term === '') {
        // Show all rows if search is empty
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

function addTablePagination(table) {
    const rowsPerPage = 20;
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.rows);
    let currentPage = 1;
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    
    if (totalPages <= 1) return;
    
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'table-pagination';
    paginationContainer.style.cssText = `
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    `;
    
    function updatePagination() {
        paginationContainer.innerHTML = '';
        
        // Bouton précédent
        const prevBtn = createPaginationButton('‹', currentPage > 1, () => {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
                updatePagination();
            }
        });
        paginationContainer.appendChild(prevBtn);
        
        // Numéros de page
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                const pageBtn = createPaginationButton(i, true, () => {
                    currentPage = i;
                    showPage(currentPage);
                    updatePagination();
                });
                
                if (i === currentPage) {
                    pageBtn.classList.add('active');
                }
                
                paginationContainer.appendChild(pageBtn);
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                ellipsis.style.padding = '0.5rem';
                paginationContainer.appendChild(ellipsis);
            }
        }
        
        // Bouton suivant
        const nextBtn = createPaginationButton('›', currentPage < totalPages, () => {
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
                updatePagination();
            }
        });
        paginationContainer.appendChild(nextBtn);
    }
    
    function createPaginationButton(text, enabled, onClick) {
        const btn = document.createElement('button');
        btn.textContent = text;
        btn.className = 'pagination-btn';
        btn.style.cssText = `
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--admin-gray-300);
            background: ${enabled ? 'var(--admin-white)' : 'var(--admin-gray-100)'};
            color: ${enabled ? 'var(--admin-gray-700)' : 'var(--admin-gray-400)'};
            cursor: ${enabled ? 'pointer' : 'not-allowed'};
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        `;
        
        if (enabled) {
            btn.addEventListener('click', onClick);
            btn.addEventListener('mouseenter', () => {
                btn.style.background = 'var(--admin-gray-50)';
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.background = btn.classList.contains('active') ? 
                    'var(--admin-accent)' : 'var(--admin-white)';
            });
        }
        
        return btn;
    }
    
    function showPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        
        rows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });
    }
    
    table.parentNode.appendChild(paginationContainer);
    showPage(1);
    updatePagination();
}

function initializeConfirmDialogs() {
    const deleteButtons = document.querySelectorAll('button[type="submit"]');
    
    deleteButtons.forEach(button => {
        if (button.textContent.includes('Supprimer') || button.classList.contains('btn-danger')) {
            button.addEventListener('click', (e) => {
                if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ? Cette opération est irréversible.')) {
                    e.preventDefault();
                }
            });
        }
    });
}

function initializeBulkActions() {
    const tables = document.querySelectorAll('.table');
    
    tables.forEach(table => {
        const checkboxes = table.querySelectorAll('input[type="checkbox"]');
        if (checkboxes.length === 0) return;
        
        // Ajouter une checkbox "Tout sélectionner"
        const headerRow = table.querySelector('thead tr');
        if (headerRow) {
            const selectAllCell = document.createElement('th');
            const selectAllCheckbox = document.createElement('input');
            selectAllCheckbox.type = 'checkbox';
            selectAllCheckbox.addEventListener('change', () => {
                checkboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
                updateBulkActions();
            });
            
            selectAllCell.appendChild(selectAllCheckbox);
            headerRow.insertBefore(selectAllCell, headerRow.firstChild);
        }
        
        // Ajouter des checkboxes aux lignes
        const bodyRows = table.querySelectorAll('tbody tr');
        bodyRows.forEach(row => {
            const checkboxCell = document.createElement('td');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.addEventListener('change', updateBulkActions);
            
            checkboxCell.appendChild(checkbox);
            row.insertBefore(checkboxCell, row.firstChild);
        });
        
        // Créer la barre d'actions groupées
        const bulkActionsBar = document.createElement('div');
        bulkActionsBar.className = 'bulk-actions-bar';
        bulkActionsBar.style.cssText = `
            display: none;
            background: var(--admin-accent);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            align-items: center;
            gap: 1rem;
        `;
        
        const selectedCount = document.createElement('span');
        selectedCount.className = 'selected-count';
        
        const bulkDeleteBtn = document.createElement('button');
        bulkDeleteBtn.textContent = 'Supprimer la sélection';
        bulkDeleteBtn.className = 'btn btn-danger btn-sm';
        bulkDeleteBtn.addEventListener('click', () => {
            if (confirm('Supprimer tous les éléments sélectionnés ?')) {
                // Logique de suppression groupée
                console.log('Suppression groupée');
            }
        });
        
        bulkActionsBar.appendChild(selectedCount);
        bulkActionsBar.appendChild(bulkDeleteBtn);
        table.parentNode.insertBefore(bulkActionsBar, table);
        
        function updateBulkActions() {
            const checkedBoxes = table.querySelectorAll('tbody input[type="checkbox"]:checked');
            const count = checkedBoxes.length;
            
            if (count > 0) {
                bulkActionsBar.style.display = 'flex';
                selectedCount.textContent = `${count} élément${count > 1 ? 's' : ''} sélectionné${count > 1 ? 's' : ''}`;
            } else {
                bulkActionsBar.style.display = 'none';
            }
        }
    });
}

function initializeStatsUpdater() {
    const statsElements = document.querySelectorAll('[data-stat]');
    
    if (statsElements.length > 0) {
        // Mettre à jour les statistiques toutes les 30 secondes
        setInterval(updateStats, 30000);
    }
}

function updateStats() {
    // Simuler la mise à jour des statistiques
    // Dans une vraie application, ceci ferait un appel AJAX
    console.log('Mise à jour des statistiques...');
}

function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K pour la recherche
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('.table-search input');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Échap pour fermer les modales/sidebars
        if (e.key === 'Escape') {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && sidebar.classList.contains('mobile-visible')) {
                sidebar.classList.remove('mobile-visible');
            }
        }
    });
}

// Styles CSS additionnels pour l'admin
const adminStyles = document.createElement('style');
adminStyles.textContent = `
    .sidebar.mobile-hidden {
        transform: translateX(-100%);
    }
    
    .sidebar.mobile-visible {
        transform: translateX(0);
    }
    
    .sort-icon {
        margin-left: 0.5rem;
        opacity: 0.5;
        transition: opacity 0.2s ease;
    }
    
    th:hover .sort-icon {
        opacity: 1;
    }
    
    .pagination-btn.active {
        background: var(--admin-accent) !important;
        color: white !important;
        border-color: var(--admin-accent) !important;
    }
    
    .bulk-actions-bar {
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 1024px) {
        .sidebar {
            transition: transform 0.3s ease;
        }
    }
`;
document.head.appendChild(adminStyles);

