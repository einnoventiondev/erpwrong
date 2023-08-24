
<form action="" id="myGrammarForm">
    @csrf
    <div class="row">
        <div class="col-12 mb-2" id="getkeywords">
            <div class="form-group" id="getkeywords">
{{--                <label for="description">{{ __('Description')}}</label>--}}
                <textarea class="form-control form-control-light mt-2" id="description" rows="3" name="description" ></textarea>
            </div>
        </div>
    </div>
</form>
<div class="response" >

    <a class="btn btn-primary btn-sm float-left" href="#!" id="regenerate">{{ __('Re Generate') }}</a>
    <a href="#!" onclick="copyGrammerText()" class="btn btn-primary btn-sm float-end "><i class="ti ti-copy"></i> {{ __('Copy Text') }}</a>
    <div class="form-group mt-3" >
        {{ Form::textarea('description', null, ['class' => 'form-control richText-editor','rows' => 1,'placeholder' => __('Description'),'id'=>'richtext']) }}

    </div>
</div>

<link rel="stylesheet" href="{{ asset('public/css/richtext.min.css') }}">
<script src="{{ asset('public/js/jquery.richtext.js') }}"></script>
<script src="{{ asset('public/js/jquery.richtext.min.js') }}"></script>
<script>
    $('body').ready(function(){
        "use strict";
        $('#richtext').richText({

            // text formatting
            bold: true,
            italic: true,
            underline: true,

            // text alignment
            leftAlign: true,
            centerAlign: true,
            rightAlign: true,
            justify: true,

            // lists
            ol: true,
            ul: true,

            // title
            heading: true,

            // fonts
            fonts: true,
            fontList: [
                "Arial",
                "Arial Black",
                "Comic Sans MS",
                "Courier New",
                "Geneva",
                "Georgia",
                "Helvetica",
                "Impact",
                "Lucida Console",
                "Tahoma",
                "Times New Roman",
                "Verdana"
            ],
            fontColor: true,
            fontSize: true,

            // uploads
            imageUpload: false,
            fileUpload: false,

            // media
            videoEmbed: false,

            // link
            urls: false,

            // tables
            table: false,

            // code
            removeStyles: false,
            code: false,

            // colors
            colors: [],

            // dropdowns
            fileHTML: '',
            imageHTML: '',

            // translations
            translations: {
                'title': 'Title',

            },

            // privacy
            youtubeCookies: false,

            // developer settings
            useSingleQuotes: false,
            height: 0,
            heightPercentage: 0,
            id: "",
            class: "",
            useParagraph: true,
            maxlength: 0,
            callback: undefined,
            useTabForNext: false
        });
    });
</script>
<script>
    $('body').ready(function(){

        if($('.grammer_textarea').length>0){

            var summernoteValue = $('.grammer_textarea').val();
        }
        else{
            $('.summernote-simple').summernote();
            var summernoteValue = $('.summernote-simple').summernote('code');
            summernoteValue = summernoteValue.replace(/<(.|\n)*?>/g, '');


        }
        $('#description').text(summernoteValue);
    })

    $('body').on('click','#regenerate',function(){
        var form=$("#myGrammarForm");
        $.ajax({
            type:'post',
            url : '{{ route('grammar.response') }}',
            datType: 'json',
            data:form.serialize(),
            beforeSend: function(msg){
                $("#regenerate").empty();
                var html = '<span class="spinner-grow spinner-grow-sm" role="status"></span>';
                $("#regenerate").append(html);
            },
            afterSend: function(msg){
                $("#regenerate").empty();
                var html = `<a class="btn btn-primary" href="#!" id="regenerate">{{ __('Generate') }}</a>`;
                $("#regenerate").replaceWith(html);

            },
            success: function(data){

                $('.response').removeClass('d-none');
                $('#regenerate').text('Re-Generate');
                let id = document.querySelector('.richText-editor').id;
                let editor = document.getElementById(id);
                if(data.message){
                    show_toastr('error', data.message, 'error');
                    $('#commonModalOver').modal('hide');
                }
                else{
                    editor.innerHTML = data;
                }
            },
        });
    });

    function copyGrammerText() {
        var r = document.createRange();
        var id2 = document.querySelector('.richText-editor').id;
        r.selectNode(document.getElementById(id2));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(r);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();

        var copied = $("#"+id2+"").text()

        if($('.grammer_textarea').length>0){
            $('.grammer_textarea').val(copied);
        }
        else{
            $('.summernote-simple').summernote("code", copied);
        }

        show_toastr('success', 'Result text has been copied successfully', 'success');
        $('#commonModalOver').modal('hide');
    }

</script>
