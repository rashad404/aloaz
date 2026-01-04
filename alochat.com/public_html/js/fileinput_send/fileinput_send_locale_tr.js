/*!
 * FileInput <_LANG_> Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinput.locales._LANG_ = {
        fileSingle: 'resim',
        filePlural: 'resimler',
        browseLabel: 'Resim seç &hellip;',
        removeLabel: 'Sil',
        removeTitle: 'Seçilmiş resimleri sil',
        cancelLabel: 'İptal et',
        cancelTitle: 'Yüklemeyi iptal et',
        uploadLabel: 'Gönder',
        uploadTitle: 'Seçilmiş resimleri yüklə',

        msgSizeTooLarge: 'File "{name}" (<b>{size} KB</b>) exceeds maximum allowed upload size of <b>{maxSize} KB</b>. Please retry your upload!',
        msgFilesTooLess: 'En az "{n}" resim seçmeniz gerekir.',

        msgFilesTooMany: '<b>({n})</b> adet resim yüklediniz.Siz en fazla  <b>{m}</b> resim yükleyebilirsiniz.!',
        msgFileNotFound: 'Resim "{name}" bulunamadı!',
        msgFileSecured: 'Güvenlik sebeplerinden  dolayı bu resim okunamadı"{name}".',
        msgFileNotReadable: 'Resim "{name}" okunamadı.',
        msgFilePreviewAborted: 'Resme önizleme durduruldu - "{name}".',
        msgFilePreviewError: 'Resim okunarken hata oluştu "{name}".',
        msgInvalidFileType: '"{name}" dosyasının tipi desteklenmiyor. Yalnız "{extensions}" tipli resimler yükleyebilirsiniz.',
        msgInvalidFileExtension: '"{name}" dosyasının tipi desteklenmiyor. Yalnız "{extensions}" tipli dosyalar yükleyebilirsiniz.',
        msgValidationError: 'Resim yüklenirken hata oluştu.',
        msgLoading: 'Resim yükleniyor  &hellip;',
        msgProgress: 'Resim yükleniyor {percent}% tamamlandı.',
        msgSelected: '{n} resim seçildi',
        msgFoldersNotAllowed: 'Yalnız resim yükləyə bilərsiniz!{n} sayda klasör iptal edildi.',
        dropZoneTitle: 'Resmi buraya sürükle ve bırak &hellip;'
    };

    $.extend($.fn.fileinput.defaults, $.fn.fileinput.locales._LANG_);
})(window.jQuery);