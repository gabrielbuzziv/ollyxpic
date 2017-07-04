import Vue from 'vue'
import {
    Dialog,
    Collapse,
    Tooltip,
    Popover,
    Message,
    MessageBox,
    Select,
    Option
} from 'element-ui'
import 'element-ui/lib/theme-default/index.css'

Vue.use(Dialog)
Vue.use(Collapse)
Vue.use(Tooltip)
Vue.use(Popover)
Vue.use(Select)
Vue.use(Option)
Vue.prototype.$message = Message
Vue.prototype.$confirm = MessageBox.confirm