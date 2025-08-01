/**
 * Fichier crud-ui.js
 * Contient les fonctions réutilisables pour les opérations CRUD
 * avec affichage de loading, toast et confirmation de suppression
 */

// Fonction de base pour créer un gestionnaire CRUD Alpine.js
function createCrudManager(options) {
    // Options par défaut
    const defaults = {
        entityName: 'élément', // Nom de l'entité gérée (ex: utilisateur, reclamation, avis)
        entityNamePlural: 'éléments', // Forme plurielle
        formId: 'form', // ID du formulaire principal
        deleteFormId: 'deleteForm', // ID du formulaire de suppression
        storeRoute: '', // Route pour ajouter
        updateRoute: '', // Route pour modifier (avec :id à remplacer)
        deleteRoute: '', // Route pour supprimer (avec :id à remplacer)
        defaultItem: {}, // Structure par défaut de l'élément
        findItem: null, // Fonction pour trouver un élément par son ID
    };

    // Fusion des options
    const config = { ...defaults, ...options };

    // Retour de l'objet Alpine
    return {
        isModalOpen: false,
        isDeleteModalOpen: false,
        isLoading: false,
        showToast: false,
        toastMessage: '',
        toastType: 'info', // 'success', 'error', 'info'
        modalTitle: '',
        formAction: '',
        deleteAction: '',
        currentItemId: null,
        currentItem: { ...config.defaultItem },
        
        init() {
            // Écouter les messages flash
            if (typeof this.$nextTick === 'function') {
                this.$nextTick(() => {
                    if (document.querySelector('meta[name="flash-success"]')) {
                        const message = document.querySelector('meta[name="flash-success"]').getAttribute('content');
                        if (message) this.showToastMessage(message, 'success');
                    }
                    
                    if (document.querySelector('meta[name="flash-error"]')) {
                        const message = document.querySelector('meta[name="flash-error"]').getAttribute('content');
                        if (message) this.showToastMessage(message, 'error');
                    }
                });
            }
        },
        
        openAddModal() {
            this.modalTitle = 'Ajouter un ' + config.entityName;
            this.formAction = config.storeRoute;
            this.currentItemId = null;
            this.resetForm();
            this.isModalOpen = true;
        },
        
        openEditModal(itemId) {
            this.modalTitle = 'Modifier le ' + config.entityName;
            this.formAction = config.updateRoute.replace(':id', itemId);
            this.currentItemId = itemId;
            
            // Trouver l'élément à éditer
            const item = config.findItem ? config.findItem(itemId) : null;
            if (item) {
                this.currentItem = { ...item };
            }
            
            this.isModalOpen = true;
        },
        
        confirmDelete(itemId) {
            this.deleteAction = config.deleteRoute.replace(':id', itemId);
            this.isDeleteModalOpen = true;
        },
        
        showToastMessage(message, type = 'info') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        },
        
        submitForm() {
            this.isLoading = true;
            this.showToastMessage('Traitement en cours...', 'info');
            
            // Soumettre le formulaire après un court délai pour que le toast s'affiche
            setTimeout(() => {
                document.getElementById(config.formId).submit();
            }, 500);
        },
        
        executeDelete() {
            this.isLoading = true;
            this.showToastMessage('Suppression en cours...', 'info');
            
            // Soumettre le formulaire après un court délai pour que le toast s'affiche
            setTimeout(() => {
                document.getElementById(config.deleteFormId).submit();
            }, 500);
        },
        
        resetForm() {
            this.currentItem = { ...config.defaultItem };
        }
    };
}
