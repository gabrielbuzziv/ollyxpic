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
            <p>You only gain experience if your level is at least 1.1x of your opponent.</p>
            
            <p>
                Experience gain is capped:
                - For level 50+ the max experience you can gain is 200% your current experience.
                - For level 50- The max experience is limited to 25% of the current level.
            </p>

            <p>
                You will only receive experience for the same players in the first 5 kills in the same SS, each 
                kill decrease the experience in 10%.
            </p>

            <p>
                Everyone will receive experience, the % is based in the dealt damage.
            </p>
        </panel>

        <panel>
            <ul>
                <li>Your level</li>
                <li>Enemy level</li>
                <li>Times killed this SS</li>
                <li>The gained experience is based in the lost experience from the enemy, so is required to calculate blessings here.</li>
            </ul>
        </panel>

        <panel>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Your Level" v-model="level">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Enemy Level" v-model="enemy">
                </div>
                
                <div class="col-md-4">
                    <el-select v-model="limit"
                            placeholder="Select if is not the first kill">
                        <el-option v-for="limit in limits" 
                            :value="limit" 
                            :key="limit"
                        >
                            {{ limit }} times
                        </el-option>
                    </el-select>
                </div>
            </div>
        </panel>

        <panel>
            <ul>
                <li><b>Your experience:</b> {{ characterExperience }}</li>
                <li><b>Enemy Experience:</b> {{ enemyExperience }}</li>
                <li><b>Max Experience:</b></li>
                <li><b>Gained Experience</b></li>
            </ul>

            The amount of experience you will receive is:
        </panel>
    

        
    </page-load>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                level: '',
                enemy: '',
                limit: '',
                limits: [1, 2, 3, 4, 5]
            }
        },

        computed: {
            experience () {
                return 0
            },

            maxExperience () {
                return 0
            },

            characterExperience () {
                return this.getExperienceByLevel(this.level)
            },

            enemyExperience () {
                return this.getExperienceByLevel(this.enemy)   
            }
        },

        methods: {
            getExperienceByLevel (level) {
                return ((50 * Math.pow(level, 3)) / 3) - (100 * Math.pow(level, 2)) + ((850 * level) / 3) - 200
            }
        }
    };
</script>