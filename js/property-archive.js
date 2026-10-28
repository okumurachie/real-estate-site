/* 物件一覧：お気に入り / お気に入りタブ / 並び替えの自動送信 */
$(function () {
    // ① お気に入りの保存場所（ブラウザ内）の名前
    const STORAGE_KEY = 'res_fav_properties';

    // ② 保存済みのお気に入りIDを読み込む（例: [12, 35, 41]）
    function readFavs() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        } catch (e) {
            return [];
        }
    }

    // ③ お気に入りIDを保存する
    function saveFavs(ids) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
        } catch (e) {}
    }

    // 現在のお気に入りID（あとで増減するので let）
    let favs = readFavs();

    // ④ 画面の見た目を、favs の中身に合わせる
    function updateView() {
        $('.js-fav').each(function () {
            const id = Number($(this).data('property-id'));
            const isFav = favs.includes(id);
            $(this)
                .attr('aria-pressed', isFav)
                .attr(
                    'aria-label',
                    isFav ? 'お気に入りから外す' : 'お気に入りに追加',
                );
        });
        $('[data-fav-count]').text(favs.length);
    }

    // ⑤ ハートを押したとき
    $(document).on('click', '.js-fav', function (e) {
        e.preventDefault();
        const id = Number($(this).data('property-id'));

        if (favs.includes(id)) {
            favs = favs.filter(function (x) {
                return x !== id;
            }); // 外す
        } else {
            favs.push(id); // 追加する
        }

        saveFavs(favs);
        updateView();
    });

    // ⑥ 「お気に入り」タブを押したとき
    $('[data-fav-tab]').on('click', function (e) {
        e.preventDefault();
        const url = new URL(this.href);
        url.searchParams.set('favs', favs.length ? favs.join(',') : '0');
        location.href = url.toString();
    });

    // ⑦ 並び替えを変えたとき、検索フォームを送信する
    $('[data-auto-submit]').on('change', function () {
        if (this.form) {
            this.form.submit();
        }
    });

    // ⑧ 最初に1回、見た目を合わせる
    updateView();
});
