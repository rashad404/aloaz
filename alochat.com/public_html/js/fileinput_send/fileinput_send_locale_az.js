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
        fileSingle: 'şəkil',
        filePlural: 'şəkillər',
        browseLabel: 'Şəkil seç &hellip;',
        removeLabel: 'Sil',
        removeTitle: 'Seçilmiş şəkilləri sil',
        cancelLabel: 'İmtina et',
        cancelTitle: 'Yükləməni dayandır',
        uploadLabel: 'Göndər',
        uploadTitle: 'Seçilmiş şəkilləri yüklə',

        msgSizeTooLarge: 'File "{name}" (<b>{size} KB</b>) exceeds maximum allowed upload size of <b>{maxSize} KB</b>. Please retry your upload!',
        msgFilesTooLess: 'Ən azı "{n}" şəkil seçməlisiniz.',

        msgFilesTooMany: '<b>({n})</b> dənə şəkil yüklədiniz.Siz ən çoxu <b>{m}</b> şəkil yükləyə bilərsiniz.!',
        msgFileNotFound: 'Şəkil "{name}" tapılmadı!',
        msgFileSecured: 'Təhlükəsizlik səbələrinə görə bu şəkil oxuna bilmir "{name}".',
        msgFileNotReadable: 'Şəkil "{name}" oxuna bilmədi.',
        msgFilePreviewAborted: 'Şəkilə ilkin bağış dayandırıldı - "{name}".',
        msgFilePreviewError: 'Şəkil oxunarkən səhv baş verdi "{name}".',
        msgInvalidFileType: '"{name}" faylının tipi dəstəklənmir. Yalnız "{extensions}" tipli fayllar yükləyə bilərsiniz.',
        msgInvalidFileExtension: '"{name}" faylının tipi dəstəklənmir. Yalnız "{extensions}" tipli fayllar yükləyə bilərsiniz.',
        msgValidationError: 'Fayl yüklənərkən səhv baş verdi',
        msgLoading: 'Şəkil yüklənir  &hellip;',
        msgProgress: 'Şəkil yüklənir {percent}% tamamlandı.',
        msgSelected: '{n} şəkil seçildi',
        msgFoldersNotAllowed: 'Yalnız şəkil yükləyə bilərsiniz!{n} sayda qovluq ləğv olundu.',
        dropZoneTitle: 'Şəkli buraya çəkin və buraxın &hellip;'
    };

    $.extend($.fn.fileinput.defaults, $.fn.fileinput.locales._LANG_);
})(window.jQuery);