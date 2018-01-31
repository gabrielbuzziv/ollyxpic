<template>
    <page-load class="experience no-padding" :loading="loading">
        <div class="row leaders">
            <div class="col-md-4" v-for="leader, index in leaders">
                <leader :leader="leader" :index="index"></leader>
            </div>
        </div>

        <panel class="highscores">
            <table class="table">
                <tbody>
                    <player :player="highscore"
                            :index="index"
                            :key="highscore.id"
                            v-for="highscore,index in highscores"/>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import Leader from './skills/Leader'
    import Player from './skills/Player'

    export default {
        props: ['leaders', 'highscores', 'loading'],

        components: { Leader, Player },
    }
</script>