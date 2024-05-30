<?php
// Définir votre clé API ici
define('MONDAY_API_KEY', 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjM2NTgzNzE0OSwiYWFpIjoxMSwidWlkIjo1MDAxODIzMywiaWFkIjoiMjAyNC0wNS0zMFQxMjozOToyOC4wMDBaIiwicGVyIjoibWU6d3JpdGUiLCJhY3RpZCI6OTc5OTU0OSwicmduIjoidXNlMSJ9.tkYDYkMOs0B6DlI52mIiLHe5237ymejXTmD_fhvEq3k'); // Remplacez par votre clé API

// Faire une requête à l'API de Monday.com
function monday_api_request($query)
{
    $api_url = 'https://api.monday.com/v2';
    $api_key = MONDAY_API_KEY; // Utiliser la clé API définie

    $response = wp_remote_post($api_url, array(
        'method'    => 'POST',
        'headers'   => array(
            'Content-Type'  => 'application/json',
            'Authorization' => $api_key,
        ),
        'body'      => json_encode(array('query' => $query)),
    ));

    if (is_wp_error($response)) {
        return 'Erreur de requête: ' . $response->get_error_message();
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}

// Récupérer les données du tableau
function get_monday_board_data($board_id)
{
    $query = '{
        boards (ids: [' . $board_id . ']) {
            name
            items_page {
                items {
                    id
                    name
                    column_values{
                        id
                        text
                    }
                }
            }
        }
    }';

    $result = monday_api_request($query);
    print_r($result);
    if (isset($result['errors'])) {
        return array('error' => $result['errors'][0]['message']);
    }

    return $result['data']['boards'][0]['items_page'];
}

// Générer le tableau HTML
function generate_monday_table($items)
{
    if (isset($items['error'])) {
        return '<p>Erreur : ' . $items['error'] . '</p>';
    }

    $output = '<table>';
    $output .= '<tr><th>Employé</th><th>Responsable</th><th>Palm 2024</th><th>Catégorie</th><th>Email</th><th>Localisation</th><th>Téléphone</th><th>Coordonnateur</th><th>Mail Coordination</th></tr>';

    foreach ($items as $item) {
        $output .= '<tr>';


        $columns = array();
        if (isset($item['column_values'])) {
            foreach ($item['column_values'] as $column) {
                $columns[$column['title']] = $column['text'];
            }
        }

        $output .= '<td>' . ($columns['Responsable'] ?? '') . '</td>';
        $output .= '<td>' . ($columns['PALM 2024'] ?? '') . '</td>';
        $output .= '<td>' . ($columns['Catégorie'] ?? '') . '</td>';
        $output .= '<td>' . ($columns['Email'] ?? '') . '</td>';
        $output .= '<td>' . ($columns['Localisation'] ?? '') . '</td>';
        $output .= '<td>' . ($columns['Téléphone'] ?? '') . '</td>';
        $output .= '<td>' . ($columns['Coordonnateur'] ?? '') . '</td>';
        $output .= '<td>' . ($columns['Mail Coordination'] ?? '') . '</td>';
        $output .= '</tr>';
    }

    $output .= '</table>';

    return $output;
}

// Shortcode pour afficher le tableau
function afficher_monday_tableau_shortcode()
{
    $board_id = '3180263669'; // Remplacez par l'ID de votre tableau
    $items = get_monday_board_data($board_id);
    return generate_monday_table($items);
}

add_shortcode('monday_tableau', 'afficher_monday_tableau_shortcode');
