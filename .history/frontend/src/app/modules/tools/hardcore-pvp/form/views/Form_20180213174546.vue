<template>
    <page-load id="imbuements">
        <page-title>
            <img :src="image_path_by_name('item', 'sudden death rune')">
            <div class="title">
                <h2>Hardcore PvP</h2>
                <span>Experience</span>
            </div>
        </page-title>

        <panel>
            <ul>
                <li>Your level</li>
                <li>Enemy level</li>
                <li>Times killed this SS</li>
                <li>Need to ask if the enemy had promotion.</li>
                <li>The gained experience is based in the lost experience from the enemy, so is required to calculate blessings here.</li>
            </ul>
        </panel>

        <panel>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Your Level" v-model="level">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Enemy Level" v-model="enemy.level">
                </div>
                
                <div class="col-md-4">
                    <el-select v-model="enemy.deaths"
                            placeholder="Select if is not the first kill">
                        <el-option v-for="death in deaths" 
                            :value="death" 
                            :key="death"
                        >
                            {{ death }} times
                        </el-option>
                    </el-select>
                </div>
            </div>
        </panel>

        <panel v-if="isValid">
            <ul>
                <li><b>Your experience:</b> {{ characterExperience }}</li>
                <li><b>Enemy lost experience:</b> {{ enemyLostExperience }}</li>
                <li><b>Min enemy level:</b> {{ minEnemyLevel }}</li>
                <li><b>Max experience:</b> {{ maxExperience }}</li>
            </ul>

            The amount of experience you will receive is:
            <b>{{ experience }}</b>
        </panel>
    

        
    </page-load>
</template>

<script type="text/babel">
export default {
    data() {
        return {
            level: "",
            enemy: {
                level: "",
                deaths: "",
                promotion: true
            },
            deaths: [1, 2, 3, 4, 5]
        }
    },

    computed: {
        isValid() {
            if (this.level == "" || this.level == null) return false
            if (this.enemy.level == "" || this.enemy.level == null) return false
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

            if (!level) return 0

            const blessing = 0.08 * 5
            const experience = this.getExperienceByLevel(level)

            let penalty =
                level <= 24
                    ? experience * 0.1
                    : (level + 50) /
                      100 *
                      (50 * (Math.pow(level, 2) - 5 * level + 8))

            const percentage = (1 - (blessing + promotion)).toFixed(2)

            return Math.ceil(penalty * percentage)
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