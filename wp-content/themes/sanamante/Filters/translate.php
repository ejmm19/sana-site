<?php
/** Translate */

/**
 * @param $translation
 * @return array|string
 */
function substitution_predefined_texts($translation): array|string
{
    $words = [
        'View more' => 'Ver más',
        'Products' => 'Productos',
        'View product' => 'Ver producto',
        'Socials' => 'Redes sociales',
        'Calendar from' => 'Calendario de',
        'Activity' => 'Actividades',
        'Nothing for show' => 'Nada para mostrar',
        'Schedule with' => 'Agendar con',
    ];
    return str_ireplace(array_keys($words), $words, $translation);
}
add_filter('gettext', 'substitution_predefined_texts');
add_filter('ngettext', 'substitution_predefined_texts');
/*<fieldset>
	<legend>Legend</legend>
	<ol>
		<li>Your Name (required) [text* text-381]</li>
		<li>Email Address (required) [email* email-607]</li>
		<li>Your Message [textarea textarea-321]</li>
	</ol>
	<p>
		* Required
</p>
</fieldset>
[submit "Send"]*/