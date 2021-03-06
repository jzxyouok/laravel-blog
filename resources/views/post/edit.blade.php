@extends('layout.main')
@section('css')
    <link rel="stylesheet" href="/css/simplemde.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="container" style="margin-top:38px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">创建文章</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="/post/{{ $post->id }}">
                            {{ csrf_field() }}
                            {{method_field("PUT")}}
                            @if(count($errors))
                                <div class="form-group">
                                    <div class="col-md-12">
                                        @foreach($errors->all() as $error)
                                            <div style="padding: 8px;
    margin-bottom: 10px;" class="alert alert-danger" role="alert">{{ $error }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-3">
                                    <select name="category_id" class="input-xlarge form-control">
                                        @foreach($categorys as $category)
                                            <option @if($post->category_id == $category->id) checked @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <input class="form-control" name="title" value="{{ $post->title }}" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control topics" multiple="multiple" name="topics[]">
                                        @foreach($post->topics as $topic)
                                            <option value="{{ $topic->id }}" selected="selected">{{ $topic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea rows="9" id="editor" style="resize:none" name="body" class="form-control">{{ $post->body  }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 col-md-offset-10">
                                    <button type="submit" class="btn btn-primary">
                                        更新文章
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="/js/simplemde.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/i18n/zh-CN.js"></script>
    <script>
        // Most options demonstrate the non-default behavior
        var simplemde = new SimpleMDE({
            autofocus: true,
            autosave: {
                enabled: true,
                uniqueId: "editor01",
                delay: 1000,
            },
            blockStyles: {
                bold: "__",
                italic: "_"
            },
            element: document.getElementById("editor"),
            forceSync: true,
            hideIcons: ["guide", "heading"],
            indentWithTabs: false,
            initialValue: "",
            insertTexts: {
                horizontalRule: ["", "\n\n-----\n\n"],
                image: ["![](http://", ")"],
                link: ["[", "](http://)"],
                table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
            },
            lineWrapping: false,
            parsingConfig: {
                allowAtxHeaderWithoutSpace: true,
                strikethrough: false,
                underscoresBreakWords: true,
            },
            placeholder: "请使用Markdown 编写，图片只支持远程url。",
            promptURLs: true,
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            },
            shortcuts: {
                drawTable: "Cmd-Alt-T"
            },
            showIcons: ["code", "table"],
            spellChecker: false,
            status: false,
            status: ["autosave", "lines", "words", "cursor"], // Optional usage
            status: ["autosave", "lines", "words", "cursor", {
                className: "keystrokes",
                defaultValue: function(el) {
                    this.keystrokes = 0;
                    el.innerHTML = "0 Keystrokes";
                },
                onUpdate: function(el) {
                    el.innerHTML = ++this.keystrokes + " Keystrokes";
                }
            }], // Another optional usage, with a custom status bar item that counts keystrokes
            styleSelectedText: false,
            tabSize: 4,
            toolbarTips: true,
        });

        function formatTopic (topic) {
            return topic.name;
        }

        function formatTopicSelection (topic) {
            return topic.name || topic.text;
        }

        $(".topics").select2({
            tags: true,
            placeholder: '选择相关话题',
            minimumInputLength: 1,
            ajax: {
                url: '/api/topics',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.topics
                    };
                },
                cache: true
            },
            templateResult: formatTopic,
            templateSelection: formatTopicSelection,
            language: "zh-CN",
            escapeMarkup: function (markup) { return markup; }
        });
    </script>
@endsection
