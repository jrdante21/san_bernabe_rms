import {createApp} from 'vue'
import router from './router'

import PrintReport from './components/PrintReport.vue'
import AdminsMenu from './components/AdminsMenu.vue'

const app  = createApp({
    components: { PrintReport, AdminsMenu },
    provide () {
        return {
            errorResponseMsg (error) {
                if (error.response !== undefined) {
                    let resp = error.response
                    let msg = (resp.data.message !== undefined) ? resp.data.message : resp.data
                    if (msg === undefined || msg == '') msg = resp.status+' '+resp.statusText 
                    return msg
                    
                } else {
                    return "Something went wrong";
                }
            }
        }
    }
});

app.use(router)
app.mount('#app')
