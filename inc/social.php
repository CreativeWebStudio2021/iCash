<?php
declare(strict_types=1);

/**
 * Profili social. Lasciare url vuoto finché non arriva il link definitivo:
 * l’icona resta visibile e non punta a un profilo inventato.
 *
 * @return list<array{id:string,label:string,url:string}>
 */
function social_profiles(): array
{
    return [
        ['id' => 'facebook', 'label' => 'Facebook', 'url' => ''],
        ['id' => 'instagram', 'label' => 'Instagram', 'url' => ''],
        ['id' => 'linkedin', 'label' => 'LinkedIn', 'url' => ''],
        ['id' => 'youtube', 'label' => 'YouTube', 'url' => ''],
    ];
}

function social_icon(string $id): string
{
    $icons = [
        'facebook' => '<path fill="currentColor" d="M14.2 8.5H17V5.5h-2.8C11.8 5.5 10 7.3 10 9.7v1.8H7.5V14H10v6.5h2.8V14h2.5l.4-2.5h-2.9V9.8c0-.7.4-1.3 1.4-1.3z"/>',
        'instagram' => '<path fill="currentColor" d="M8 4.5h8A3.5 3.5 0 0 1 19.5 8v8a3.5 3.5 0 0 1-3.5 3.5H8A3.5 3.5 0 0 1 4.5 16V8A3.5 3.5 0 0 1 8 4.5zm8 1.6H8A1.9 1.9 0 0 0 6.1 8v8A1.9 1.9 0 0 0 8 17.9h8A1.9 1.9 0 0 0 17.9 16V8A1.9 1.9 0 0 0 16 6.1zM12 8.4A3.6 3.6 0 1 1 8.4 12 3.6 3.6 0 0 1 12 8.4zm0 1.6A2 2 0 1 0 14 12a2 2 0 0 0-2-2zm4.35-2.55a.9.9 0 1 1-.9.9.9.9 0 0 1 .9-.9z"/>',
        'linkedin' => '<path fill="currentColor" d="M6.7 9.2H4.2V19h2.5V9.2zM5.45 4.5a1.55 1.55 0 1 0 .05 3.1 1.55 1.55 0 0 0-.05-3.1zM19.8 19h-2.5v-5.2c0-1.5-.55-2.5-1.9-2.5a2 2 0 0 0-1.9 1.4 2.4 2.4 0 0 0-.1.9V19H10.9V9.2h2.4v1.4a3.2 3.2 0 0 1 2.9-1.6c2.1 0 3.6 1.4 3.6 4.3V19z"/>',
        'youtube' => '<path fill="currentColor" d="M21.6 8.2a2.5 2.5 0 0 0-1.8-1.8C18.3 6 12 6 12 6s-6.3 0-7.8.4a2.5 2.5 0 0 0-1.8 1.8C2 9.7 2 12 2 12s0 2.3.4 3.8a2.5 2.5 0 0 0 1.8 1.8C5.7 18 12 18 12 18s6.3 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8c.4-1.5.4-3.8.4-3.8s0-2.3-.4-3.8zM10.2 14.8V9.2L15.2 12l-5 2.8z"/>',
    ];
    $path = $icons[$id] ?? '';
    return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $path . '</svg>';
}

function render_social(string $variant = 'dark'): void
{
    $class = $variant === 'light' ? 'socials socials-light' : 'socials socials-dark';
    echo '<ul class="' . $class . '">';
    foreach (social_profiles() as $profile) {
        $label = $profile['label'];
        $url = trim($profile['url']);
        $icon = social_icon($profile['id']);
        echo '<li>';
        if ($url !== '') {
            echo '<a href="' . e($url) . '" target="_blank" rel="noopener" title="' . e($label) . '" aria-label="' . e($label) . '">' . $icon . '</a>';
        } else {
            $pending = $label . ', link in arrivo';
            echo '<span title="' . e($pending) . '" aria-label="' . e($pending) . '">' . $icon . '</span>';
        }
        echo '</li>';
    }
    echo '</ul>';
}
