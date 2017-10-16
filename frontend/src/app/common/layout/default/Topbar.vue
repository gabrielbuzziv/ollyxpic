<template>
    <div id="topbar">
        <div class="container">
            <div class="pull-left">
                <span class="rashid">
                    <img src="/src/assets/images/rashid.gif" alt="">
                    <span>
                        <span class="welcome">Hello, my name is Rashid</span>
                        <span class="visit">come visit me in <b>{{ rashid }}</b></span>
                    </span>
                </span>
            </div>

            <div class="pull-right">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="J8VPUH8PS9ANS">
                    <button name="submit" class="btn btn-default" target="_blank">
                        <i class="mdi mdi-coffee margin-right-5"></i>
                        Pay us a cooffe
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            rashid () {
                const cities = ['Carlin', 'Svargrond', 'Libert Bay', 'Port Hope', 'Ankrahmun', 'Darashia', 'Edron']
                const today = moment(new Date()).day()

                return cities[today]
            }
        }
    }
</script>