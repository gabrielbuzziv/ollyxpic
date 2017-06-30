import Vue from 'vue'
import {
    Dialog,
    Collapse,
    Tooltip,
    Popover,
    Message,
    MessageBox
} from 'element-ui'
import 'element-ui/lib/theme-default/index.css'

Vue.use(Dialog)
Vue.use(Collapse)
Vue.use(Tooltip)
Vue.use(Popover)
Vue.prototype.$message = Message
Vue.prototype.$confirm = MessageBox.confirm