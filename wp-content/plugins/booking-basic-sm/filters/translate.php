<?php
/** Translate */

/**
 * @param $translation
 * @return array|string
 */
function translation_texts($translation): array|string
{
    $words = [
        'Full calendar' => 'Calendario completo',
        'Employees' => 'Orientadores',
        'Scheduling' => 'Agendamiento',
        'Employee' => 'Orientador',
        'Schedule date' => 'Fecha programada',
        'Init date' => 'Inicia',
        'End date' => 'Finaliza',
        'Phone' => 'Teléfonos de contacto',
        'Created at' => 'Fecha de creación',
        'Emails' => 'Correos destino sanamente',
        'Base value per hour' => 'Valor base por hora',
        'WhatsApp number' => 'Número de WhatsApp de contacto',
        'Add phones separated by comma ,' => 'Agregue teléfonos separados por coma,',
        'Add emails separated by comma ,' => 'Agregar correos electrónicos separados por coma,',
    ];
    return str_ireplace(array_keys($words), $words, $translation);
}
add_filter('gettext', 'translation_texts');
add_filter('ngettext', 'translation_texts');