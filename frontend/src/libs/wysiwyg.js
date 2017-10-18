import Vue from 'vue'
import VueSummernote from 'vue-summernote'

require('bootstrap')
require('summernote/dist/summernote.css')

Vue.use(VueSummernote, {
    dialogsFade: true,
    lang: 'en-US',
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
    ],
    disableResizeEditor: true
})