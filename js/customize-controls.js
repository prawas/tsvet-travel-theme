(function($, api) {
    $(function(){
        tinyMCE.on('SetupEditor', function(editor) {
            if(editor.id === "svg_custom_logo") {
                editor.on('BeforeSetContent', function(args) {
                    //заменяем пустой контент на '\n', чтобы не добавлялась строка '<br data-mce-bogus="1">'
                    args.content = args.content || '\n';
                    //отменяем обработку html тегов
                    args.format = 'raw';
                });
                editor.on('BeforeGetContent', function(args) {
                    //отменяем обработку html тегов
                    args.format = 'raw';
                });
                editor.on('GetContent', function(args) {
                    if(args.content === 'default') {
                        //заменяем значение 'default' на svg логотип по умолчанию
                        args.content = $('#svg_custom_logo-default').html();
                        this.dom.setHTML(this.getBody(), args.content);
                    }
                });
                editor.on('Change KeyUp', function () {
                    this.save();
                    $(this.targetElm).trigger('change');
                });
            }
        });

        //присваиваем области просмотра 'z-index: 1', чтобы были видны всплывающие окна
        $('.wp-full-overlay').css('z-index', 1);
    });
})(jQuery, wp.customize);