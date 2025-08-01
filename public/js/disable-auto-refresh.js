/**
 * Ce script empêche le rechargement automatique des pages
 * Il intercepte les tentatives de rechargement automatique qui peuvent être causées par des
 * bibliothèques tierces ou des scripts injectés
 */
(function() {
    // Sauvegarde des méthodes originales qui pourraient être utilisées pour recharger la page
    const originalSetInterval = window.setInterval;
    const originalSetTimeout = window.setTimeout;
    const originalReload = window.location.reload;
    const originalReplace = window.location.replace;
    const originalAssign = window.location.assign;
    
    // Remplace setInterval pour surveiller et potentiellement bloquer les rechargements automatiques
    window.setInterval = function(callback, delay, ...args) {
        // Vérifie si le callback contient du code qui recharge la page
        if (typeof callback === 'function' || typeof callback === 'string') {
            const callbackStr = callback.toString();
            if (callbackStr.includes('location.reload') || 
                callbackStr.includes('location.href') || 
                callbackStr.includes('location.replace')) {
                
                console.warn('Tentative de rechargement automatique bloquée', callbackStr);
                
                // Empêcher les rechargements trop fréquents (< 30s)
                if (delay && delay < 30000) {
                    console.warn(`Intervalle trop court (${delay}ms) - ignoré pour éviter les rechargements fréquents`);
                    // Retourne un faux ID d'intervalle
                    return -1;
                }
            }
        }
        
        // Autorise les autres intervalles normaux
        return originalSetInterval(callback, delay, ...args);
    };
    
    // Redéfinit la méthode reload pour bloquer les rechargements automatiques fréquents
    let lastReloadTime = 0;
    window.location.reload = function(forceGet) {
        const now = Date.now();
        // Empêche les rechargements multiples en moins de 10 secondes
        if (now - lastReloadTime < 10000) {
            console.warn('Rechargement de page trop fréquent bloqué');
            return;
        }
        
        lastReloadTime = now;
        return originalReload.call(window.location, forceGet);
    };
    
    // Fonction pour détecter et intercepter les rechargements automatiques
    function detectAutoRefresh() {
        // Recherche les métabalises de rechargement automatique
        const metaRefresh = document.querySelector('meta[http-equiv="refresh"]');
        if (metaRefresh) {
            console.warn('Meta refresh détecté et désactivé', metaRefresh);
            metaRefresh.remove();
        }
        
        // Recherche des scripts suspects
        const scripts = document.querySelectorAll('script');
        scripts.forEach(script => {
            if (script.textContent && (
                script.textContent.includes('location.reload') ||
                script.textContent.includes('setInterval') && 
                script.textContent.includes('reload')
            )) {
                console.warn('Script de rechargement automatique potentiel détecté', script.textContent);
            }
        });
    }
    
    // Exécute la détection après le chargement de la page
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', detectAutoRefresh);
    } else {
        detectAutoRefresh();
    }
    
    console.log('Protection contre les rechargements automatiques activée');
})();
