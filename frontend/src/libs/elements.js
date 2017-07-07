import Vue from 'vue'
import {
    Dialog,
    Collapse,
    Tooltip,
    Popover,
    Message,
    MessageBox,
    Select,
    Option,
    Checkbox,
    Radio
} from 'element-ui'
import 'element-ui/lib/theme-default/index.css'

Vue.use(Dialog)
Vue.use(Collapse)
Vue.use(Tooltip)
Vue.use(Popover)
Vue.use(Select)
Vue.use(Option)
Vue.use(Checkbox)
Vue.use(Radio)
Vue.prototype.$message = Message
Vue.prototype.$confirm = MessageBox.confirm