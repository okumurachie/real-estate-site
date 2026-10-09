<section id="top" class="hero">
    <ul class="hero__bg">
        <?php for ($i = 1; $i <= 3; $i++) : ?>
            <li class="hero__bg-item">
                <img
                    src="<?php echo esc_url(get_template_directory_uri() . '/img/front/top' . $i . '.jpeg') ?>"
                    alt="mainvisual<?php echo $i; ?>"
                    <?php echo $i === 1 ? '' : 'loading="lazy"'; ?> />
            </li>
        <?php endfor; ?>
    </ul>

    <div class="hero__content">
        <p class="hero__lead">地域密着の不動産会社</p>
        <h2 class="hero__catch">
            暮らしに、ちゃんと<br>
            寄りそう住まい探し
        </h2>

        <p class="hero__text">一人暮らしも、ご家族も。この街を知っているから、<br>
            暮らしに合わせた住まいをご提供できます。
        </p>

        <form action="<?php echo esc_url(get_post_type_archive_link('property')); ?>" method="get" class="hero__form">
            <p class="hero__form-label">物件を探す</p>
            <ul class="hero__form-field">
                <li>
                    <label for="hero-area" class="screen-reader-text">エリア</label>
                    <select name="area" id="hero-area">
                        <option value="">エリア</option>
                        <option value="otsu">大津市</option>
                        <option value="kusatsu">草津市</option>
                        <option value="moriyama">守山市</option>
                        <option value="ritto">栗東市</option>
                    </select>
                </li>
                <li>
                    <label for="hero-type" class="screen-reader-text">売買・賃貸</label>
                    <select name="type" id="hero-type">
                        <option value="">購入・賃貸</option>
                        <option value="buy">購入</option>
                        <option value="rent">賃貸</option>
                    </select>
                </li>
                <li data-price="buy">
                    <label for="hero-price" class="screen-reader-text">価格帯</label>
                    <select name="price" id="hero-price-buy">
                        <option value="">価格帯</option>
                        <option value="0-2000">〜2,000万円</option>
                        <option value="2000-3000">2,000万〜3,000万円</option>
                        <option value="3000-4000">3,000万〜4,000万円</option>
                        <option value="4000-">4,000万円〜</option>
                    </select>
                </li>
                <li data-price="rent" hidden>
                    <label for="hero-price" class="screen-reader-text">価格帯</label>
                    <select name="price" id="hero-price-rent" disabled>
                        <option value="">家賃</option>
                        <option value="0-50000">〜5万円</option>
                        <option value="50000-80000">5万〜8万円</option>
                        <option value="80000-120000">8万〜12万円</option>
                        <option value="120000-">12万円〜</option>
                    </select>
                </li>
            </ul>

            <button type="submit">検索する</button>
        </form>
    </div>
</section>
