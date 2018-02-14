<template>
    <page-load id="hardcore">
        <page-title>
            <img :src="image_path_by_name('item', 'sudden death rune')">
            <div class="title">
                <h2>Hardcore PvP</h2>
                <span>Experience</span>
            </div>
        </page-title>

        <div class="row form">
            <div class="col-md-4">
                <panel>
                    <input type="text" class="form-control" placeholder="Your Level" v-model="level">
                </panel>
            </div>

            <div class="col-md-8">
                <panel>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="Enemy Level" v-model="enemy.level">
                        </div>

                        <div class="col-md-4">
                            <el-checkbox v-model="enemy.promotion">Promotion</el-checkbox>
                            <el-checkbox v-model="enemy.blessing">Blessing</el-checkbox>
                        </div>
                    </div>
                </panel>
            </div>
        </div>

        <div class="result" v-if="isValid">
            <div class="alert alert-info">
                <p>The enemy loose <b>~{{ enemyLostExperience | experience }}</b> experience points.</p>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <li>First frag: {{ experience | reduce(0) | experience }}</li>
                </div>
            </div>
        </div>

        <panel class="result" v-if="isValid">
            

            <ul class="frags">
                
                <li>Second frag: {{ experience | reduce(0.1) | experience }}</li>
                <li>Third frag: {{ experience | reduce(0.2) | experience }}</li>
                <li>Fourth frag: {{ experience | reduce(0.3) | experience }}</li>
                <li>Fifth frag: {{ experience | reduce(0.4) | experience }}</li>
            </ul>
        </panel>
    

        
    </page-load>
</template>

<script>
Number.prototype.format = function(n, x) {
    var re = "\\d(?=(\\d{" + (x || 3) + "})+" + (n > 0 ? "\\." : "$") + ")"
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, "g"), "$&.")
}

export default {
    data() {
        return {
            level: "",
            enemy: {
                level: "",
                promotion: true,
                blessing: true
            }
        }
    },

    computed: {
        isValid() {
            if (this.level == "" || this.level == null) return false
            if (this.enemy.level == "" || this.enemy.level == null) return false

            return true
        },

        experience() {
            if (this.enemy.level < this.minEnemyLevel) return 0

            const penalty = 1 - this.enemy.deaths * 0.1
            const experience = this.enemyLostExperience * penalty

            return experience < this.maxExperience
                ? experience
                : this.maxExperience
        },

        minEnemyLevel() {
            return Math.ceil(this.level * 100 / 110)
        },

        maxExperience() {
            return this.level >= 50
                ? this.getExperienceByLevel(this.level) * 2
                : this.getExperienceByLevel(this.level) * 0.25
        },

        characterExperience() {
            return this.getExperienceByLevel(this.level)
        },

        enemyLostExperience() {
            const level = parseInt(this.enemy.level)
            const promotion = this.enemy.promotion ? 0.3 : 0
            const blessing = this.enemy.blessing ? 0.08 * 5 : 0

            if (!level) return 0

            const experience = this.getExperienceByLevel(level)

            let penalty =
                level <= 24
                    ? experience * 0.1
                    : (level + 50) /
                      100 *
                      (50 * (Math.pow(level, 2) - 5 * level + 8))

            const percentage = (1 - (blessing + promotion)).toFixed(2) * 1.16

            return Math.ceil(penalty * percentage)
        }
    },

    filters: {
        experience(data) {
            return data ? data.format() : 0
        },

        reduce(data, amount) {
            return data ? data * (1 - amount) : data
        }
    },

    methods: {
        getExperienceByLevel(level) {
            return (
                50 * Math.pow(level, 3) / 3 -
                100 * Math.pow(level, 2) +
                850 * level / 3 -
                200
            )
        }
    }
}
</script>