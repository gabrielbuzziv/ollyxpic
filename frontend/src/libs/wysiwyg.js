import Vue from 'vue'
import VueSummernote from 'vue-summernote'

require('bootstrap')
require('summernote/dist/summernote.css')
require('codemirror')
require('codemirror/mode/htmlembedded/htmlembedded')

Vue.use(VueSummernote, {
    dialogsFade: true,
    lang: 'en-US',
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['table', ['table']]
    ],
    codemirror: {
        theme: 'monokai',
        htmlMode: true,
        lineNumbers: true,
        mode: 'text/html'
    }
})