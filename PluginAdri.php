<?php
/**
 * Nombre del Plugin: Modificador y Registrador de Títulos
 * Descripción: Modifica los títulos de las publicaciones y registra los cambios.
 * Versión: 1.0
 * Autor: TuNombre
 */

// Asegurarse de que WordPress esté cargado
defined( 'ABSPATH' ) or die( '¡No se permite el acceso directo a este script!' );

// Función para modificar y registrar el título
function modificar_y_registrar_titulo($titulo) {
    global $wpdb;

    // Añadir etiqueta al título
    $titulo_modificado = $titulo . " [Etiquetado]";

    // Insertar en la base de datos
    $wpdb->insert(
        $wpdb->prefix . 'cambios_titulo',
        array('titulo_original' => $titulo, 'titulo_modificado' => $titulo_modificado),
        array('%s', '%s')
    );

    return $titulo_modificado;
}

// Función para crear la tabla en la base de datos
function crear_tabla_registro() {
    global $wpdb;

    $nombre_tabla = $wpdb->prefix . 'cambios_titulo';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $nombre_tabla (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        titulo_original TEXT NOT NULL,
        titulo_modificado TEXT NOT NULL,
        fecha datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

// Enganchar la función de creación de tabla a la activación del plugin
register_activation_hook( __FILE__, 'crear_tabla_registro' );

// Enganchar la función de modificación de título al filtro de título
add_filter('the_title', 'modificar_y_registrar_titulo');
?>
