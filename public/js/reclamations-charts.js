// Initialisation des graphiques et interactions pour la page des réclamations
document.addEventListener('DOMContentLoaded', function() {
    console.log("Initialisation des graphiques et popups...");
    // Référence aux éléments HTML
    const statusChartEl = document.getElementById('statusChart');
    const monthlyChartEl = document.getElementById('monthlyChart');
    const priorityChartEl = document.getElementById('priorityChart');
    const resolutionTimeChartEl = document.getElementById('resolutionTimeChart');
    
    // Initialiser les gestionnaires d'événements pour les étoiles
    initStarRating();
    
    // Récupérer les données des réclamations
    // Utilise la variable window.reclamationsData définie dans le code HTML
    if (window.reclamationsData) {
        if (statusChartEl) {
            initStatusChart(window.reclamationsData);
        }
        
        if (monthlyChartEl) {
            initMonthlyChart(window.reclamationsData);
        }
        
        if (priorityChartEl) {
            initPriorityChart(window.reclamationsData);
        }
        
        if (resolutionTimeChartEl) {
            initResolutionTimeChart(window.reclamationsData);
        }
    } else {
        console.error("Données des réclamations non disponibles");
    }
});

// Fonction pour ouvrir un popup
function openPopup(popupId, button = null) {
    console.log("Ouverture du popup:", popupId);
    try {
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById(popupId).classList.remove('hidden');

        if (popupId === 'detailsPopup' && button) {
            console.log("Données du bouton:", button.dataset);
            // Populate popup with reclamation data
            document.getElementById('reclamation-title').textContent = button.dataset.titre;
            document.getElementById('reclamation-description').textContent = button.dataset.description;
            document.getElementById('reclamation-created-at').textContent = 'Créée le: ' + button.dataset.createdAt;
            document.getElementById('reclamation-priorite').textContent = 'Priorité: ' + button.dataset.priorite;
            document.getElementById('reclamation-status').textContent = button.dataset.status;
            document.getElementById('reclamation-status').className = 'px-3 py-1 text-xs font-medium rounded-full ' + button.dataset.statusClass;
            document.getElementById('reclamation-updated-at').textContent = 'Dernière mise à jour: ' + button.dataset.updatedAt;
            document.getElementById('reclamation-agent').textContent = button.dataset.agent;
            document.getElementById('comment-reclamation-id').value = button.dataset.id;
            
            // Si le formulaire de feedback existe
            if (document.getElementById('feedback-reclamation-id')) {
                document.getElementById('feedback-reclamation-id').value = button.dataset.id;
                
                // Set feedback form action dynamically
                const feedbackForm = document.getElementById('feedback-form');
                if (feedbackForm) {
                    feedbackForm.action = `/reclamations/${button.dataset.id}/feedback`;
                }
            }

            // Show feedback button only if agent_id is not null and satisfaction is not set
            const feedbackButton = document.getElementById('feedback-button');
            if (feedbackButton) {
                if (button.dataset.agentId && !button.dataset.satisfaction) {
                    feedbackButton.classList.remove('hidden');
                } else {
                    feedbackButton.classList.add('hidden');
                }
            }

            // Display existing feedback if available
            if (typeof displayExistingFeedback === 'function') {
                displayExistingFeedback(button.dataset.satisfaction);
            }
            
            // Fetch comments
            if (typeof fetchComments === 'function') {
                fetchComments(button.dataset.id);
            }
        }
    } catch (error) {
        console.error("Erreur lors de l'ouverture du popup:", error);
    }
}

// Fonction pour initialiser les événements de notation par étoiles
function initStarRating() {
    const starLabels = document.querySelectorAll('#star-rating label');
    const starInputs = document.querySelectorAll('#star-rating input');
    
    if (starLabels.length > 0) {
        starLabels.forEach((label, index) => {
            label.addEventListener('click', function() {
                updateStarRating(5 - index);
                starInputs[index].checked = true;
            });
        });
    }
}

// Fonction pour mettre à jour l'affichage des étoiles
function updateStarRating(rating) {
    window.selectedRating = rating;
    const labels = document.querySelectorAll('#star-rating label');
    labels.forEach((label, index) => {
        if (index < rating) {
            label.classList.add('text-yellow-400');
            label.classList.remove('text-gray-300');
        } else {
            label.classList.remove('text-yellow-400');
            label.classList.add('text-gray-300');
        }
    });
}

// Fonction pour afficher les feedbacks existants
function displayExistingFeedback(rating) {
    const feedbackDiv = document.getElementById('existing-feedback');
    if (!feedbackDiv) return;
    
    if (rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<span class="text-xl ${i <= rating ? 'text-yellow-400' : 'text-gray-300'}">★</span>`;
        }
        feedbackDiv.innerHTML = `<p class="text-sm text-gray-700">Votre évaluation: ${stars}</p>`;
    } else {
        feedbackDiv.innerHTML = '';
    }
}

// Fonction pour réinitialiser les étoiles
function resetStarRating() {
    window.selectedRating = 0;
    const labels = document.querySelectorAll('#star-rating label');
    labels.forEach(label => {
        label.classList.remove('text-yellow-400');
        label.classList.add('text-gray-300');
    });
    const inputs = document.querySelectorAll('#star-rating input');
    inputs.forEach(input => input.checked = false);
}

// Fonction pour fermer le popup
function closePopup(popupId) {
    console.log("Fermeture du popup:", popupId);
    try {
        document.getElementById('overlay').classList.add('hidden');
        document.getElementById(popupId).classList.add('hidden');
        
        // Reset le formulaire si on ferme un popup avec formulaire
        if (popupId === 'reclamationPopup' || popupId === 'editPopup') {
            const form = document.getElementById(popupId).querySelector('form');
            if (form) form.reset();
        }
        
        // Réinitialiser le feedback
        if (popupId === 'feedbackPopup') {
            resetStarRating();
            const commentaireEl = document.getElementById('commentaire');
            if (commentaireEl) commentaireEl.value = '';
        }

        // Masquer le formulaire de commentaire
        const commentForm = document.getElementById('comment-form');
        if (commentForm) {
            commentForm.classList.add('hidden');
        }
    } catch (error) {
        console.error("Erreur lors de la fermeture du popup:", error);
    }
}

// Fonction pour initialiser le graphique de statut
function initStatusChart(reclamations) {
    // Configuration de Chart.js
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6B7280';
    Chart.defaults.plugins.legend.display = false;
    
    // 1. Graphique de distribution par statut
    const statusCounts = {
        'résolue': 0,
        'en cours': 0,
        'rejetée': 0,
        'en attente': 0
    };
    
    reclamations.forEach(rec => {
        if (statusCounts.hasOwnProperty(rec.status)) {
            statusCounts[rec.status]++;
        } else {
            statusCounts['en attente']++;
        }
    });
    
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusCounts),
            datasets: [{
                data: Object.values(statusCounts),
                backgroundColor: [
                    '#10B981', // vert - résolue
                    '#FBBF24', // jaune - en cours
                    '#EF4444', // rouge - rejetée
                    '#F97316'  // orange - en attente
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Fonction pour initialiser le graphique mensuel
function initMonthlyChart(reclamations) {
    // 2. Graphique par mois
    const monthlyData = {};
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // Initialiser les mois des 12 derniers mois
    const today = new Date();
    for (let i = 0; i < 12; i++) {
        const d = new Date(today);
        d.setMonth(today.getMonth() - i);
        const monthYear = months[d.getMonth()] + ' ' + d.getFullYear();
        monthlyData[monthYear] = 0;
    }
    
    reclamations.forEach(rec => {
        const date = new Date(rec.created_at);
        const monthYear = months[date.getMonth()] + ' ' + date.getFullYear();
        
        if (monthlyData.hasOwnProperty(monthYear)) {
            monthlyData[monthYear]++;
        }
    });
    
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(monthlyData).reverse(),
            datasets: [{
                label: 'Réclamations',
                data: Object.values(monthlyData).reverse(),
                backgroundColor: '#3B82F6',
                borderRadius: 6,
                barThickness: 15,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

// Fonction pour initialiser le graphique de priorité
function initPriorityChart(reclamations) {
    // 3. Graphique par priorité
    const priorityCounts = {
        'faible': 0,
        'moyenne': 0,
        'elevee': 0
    };
    
    reclamations.forEach(rec => {
        if (priorityCounts.hasOwnProperty(rec.priorite)) {
            priorityCounts[rec.priorite]++;
        } else {
            priorityCounts['moyenne']++;
        }
    });
    
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'pie',
        data: {
            labels: ['Élevée', 'Moyenne', 'Faible'],
            datasets: [{
                data: [priorityCounts['elevee'], priorityCounts['moyenne'], priorityCounts['faible']],
                backgroundColor: [
                    '#EF4444', // Rouge - haute
                    '#F59E0B', // Orange - moyenne
                    '#10B981'  // Vert - basse
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Fonction pour initialiser le graphique de temps de résolution
function initResolutionTimeChart(reclamations) {
    // 4. Graphique de temps de résolution moyen
    // Calculer le temps moyen de résolution en jours pour les réclamations résolues
    let totalResolutionDays = 0;
    let resolvedCount = 0;
    
    const resolutionTimes = [0, 0, 0, 0, 0];
    
    reclamations.forEach(rec => {
        if (rec.status === 'résolue' && rec.created_at && rec.updated_at) {
            const createdDate = new Date(rec.created_at);
            const resolvedDate = new Date(rec.updated_at);
            const diffTime = Math.abs(resolvedDate - createdDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            totalResolutionDays += diffDays;
            resolvedCount++;
            
            // Grouper par catégorie de temps
            if (diffDays <= 1) {
                resolutionTimes[0]++;
            } else if (diffDays <= 3) {
                resolutionTimes[1]++;
            } else if (diffDays <= 7) {
                resolutionTimes[2]++;
            } else if (diffDays <= 14) {
                resolutionTimes[3]++;
            } else {
                resolutionTimes[4]++;
            }
        }
    });
    
    const avgResolutionDays = resolvedCount > 0 ? (totalResolutionDays / resolvedCount).toFixed(1) : 0;
    
    const resolutionTimeCtx = document.getElementById('resolutionTimeChart').getContext('2d');
    new Chart(resolutionTimeCtx, {
        type: 'bar',
        data: {
            labels: ['1 jour ou moins', '2-3 jours', '4-7 jours', '8-14 jours', 'Plus de 14 jours'],
            datasets: [{
                label: 'Nombre de réclamations',
                data: resolutionTimes,
                backgroundColor: '#8B5CF6',
                borderRadius: 6,
                barThickness: 20,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return tooltipItems[0].label;
                        },
                        label: function(context) {
                            return `${context.raw} réclamation(s)`;
                        },
                        afterLabel: function() {
                            return `Temps moyen de résolution: ${avgResolutionDays} jours`;
                        }
                    }
                }
            }
        }
    });
}
