<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Mostra la pagina "Gestione Banner" con una tabella (WP_List_Table)
 */
function bannerlytics_gestione_banner_page() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Gestione Banner</h1>
        <a href="<?php echo admin_url('admin.php?page=bannerlytics-editor-banner'); ?>" class="page-title-action">Aggiungi Nuovo</a>
        <hr class="wp-header-end">

        <?php
        // Crea l'istanza della classe personalizzata
        $banner_table = new Bannerlytics_Banner_List_Table();
        // Prepara i dati (query + paginazione)
        $banner_table->prepare_items();
        ?>
        
        <form method="get">
            <!-- Importante per mantenere il contesto della pagina e mostrare i controlli corretti -->
            <input type="hidden" name="page" value="bannerlytics-gestione-banner">
            <?php
            // Stampa la tabella
            $banner_table->display();
            ?>
        </form>
    </div>
    <?php
}

// Nota: NON agganciamo la pagina al menu qui,
//       perché la voce "Gestione Banner" è già definita in menu.php.

/**
 * Assicuriamoci che la classe WP_List_Table sia caricata
 */
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Classe personalizzata per elencare i Banner
 */
class Bannerlytics_Banner_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'banner',
            'plural'   => 'banners',
            'ajax'     => false // Nessun caricamento AJAX
        ));
    }

    /**
     * Definizione delle colonne della tabella
     */
    public function get_columns() {
        return array(
            'cb'        => '<input type="checkbox" />', // Checkbox selezione multipla
            'title'     => 'Titolo',
            'shortcode' => 'Shortcode',
            'date'      => 'Data'
        );
    }

    /**
     * Gestione dei dati per ciascuna colonna
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':
                // Linka il titolo al nostro editor personalizzato
                return sprintf(
                    '<strong><a href="%s">%s</a></strong>',
                    admin_url('admin.php?page=bannerlytics-editor-banner&banner_id=' . $item['ID']),
                    esc_html($item['post_title'])
                );

            case 'shortcode':
                // Mostra lo shortcode [banner id="XXX"]
                return '[banner id="' . $item['ID'] . '"]';

            case 'date':
                // Formato data
                return date('d-m-Y', strtotime($item['post_date']));

            default:
                // Per debug, se una colonna non è definita in get_columns()
                return print_r($item, true);
        }
    }

    /**
     * Colonna dedicata alla checkbox
     */
    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="banner_id[]" value="%s" />', $item['ID']);
    }

    /**
     * Prepara dati e paginazione
     */
    public function prepare_items() {
        $per_page = 10;                    // Banner per pagina
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        // Query per recuperare i banner
        $args = array(
            'post_type'      => 'banner',
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'post_status'    => 'publish',
        );
        $query = new WP_Query($args);

        // Trasforma i dati in un array
        $data = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $data[] = array(
                    'ID'         => get_the_ID(),
                    'post_title' => get_the_title(),
                    'post_date'  => get_the_date('Y-m-d H:i:s'),
                );
            }
        }
        wp_reset_postdata();

        // Calcolo per la paginazione
        $total_items = $query->found_posts;

        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page),
        ));
    }
}
