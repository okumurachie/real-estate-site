<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body>
    <header class="header">
        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>">すまいる不動産</a></h1>

        <nav class="header-navi" id="global-nav">
            <ul>
                <li>
                    <a href="<?php echo esc_url(get_post_type_archive_link('property')); ?>" class="navi-link">物件を探す</a>
                </li>
                <li>
                    <a href="#" class="navi-link">エリア情報</a>
                </li>
                <li>
                    <a href="#" class="navi-link">お客様の声</a>
                </li>
                <li>
                    <a href="#" class="navi-link">会社情報</a>
                </li>
                <li>
                    <a href="#" class="navi-link">お知らせ</a>
                </li>
                <li>
                    <a href="#" class="contact-button">お問い合わせ</a>
                    <p>地域密着の不動産会社</p>
                </li>
            </ul>
        </nav>

        <button class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </header>
