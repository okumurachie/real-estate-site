<?php

/**
 * 共通コンポーネント：セクション見出し
 *
 * 使い方:
 *   get_template_part( 'php/components/section-head', null, [
 *       'title' => '見出し',
 *       'lead'  => 'リード文（省略可）',
 *   ] );
 */

$title = $args['title'] ?? '';
$lead  = $args['lead'] ?? '';

// タイトルが空なら何も出さない
if ($title === '') {
    return;
}
?>

<div class="section-head">
    <h2 class="section-head__title"><?php echo esc_html($title); ?></h2>
    <?php if ($lead !== '') : ?>
        <p class="section-head__lead"><?php echo esc_html($lead); ?></p>
    <?php endif; ?>
</div>
