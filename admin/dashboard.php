<?php
if (!defined('ABSPATH')) {
    exit; 
}

/**
 * =========================================================================
 * File: dashboard.php
 * Scopo: Definire la funzione di callback "bannerlytics_dashboard_page"
 *        che stampa una pagina "placeholder" per la Dashboard di BannerLytics.
 * =========================================================================
 */

function bannerlytics_dashboard_page() {
    // HTML di base (esempio)
    echo '<div class="wrap">';
    echo '    <h1>BannerLytics - Dashboard</h1>';
    echo '    <p>Benvenuto nella Dashboard di BannerLytics. Qui potrai vedere le statistiche e una panoramica dei tuoi banner.</p>';
    echo '</div>';
}
