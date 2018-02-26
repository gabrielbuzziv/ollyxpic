<template>
    <div class="row">
        <div class="col-md-6">
            <panel class="character level-panel">
                <div class="details">
                    <div class="level">
                        <div class="data">
                            <span>Level</span>
                            <b>{{ player.level }}</b>
                        </div>

                        <el-progress :percentage="levelPercentage" :show-text="false"
                                     :stroke-width="12"/>
                    </div>

                    <div class="skills">
                        <div v-if="skills.length">
                            <div class="skill" v-if="magicLevel">
                                <i class="mdi mdi-auto-fix icon"></i>
                                <div class="data">
                                    <b>Magic Level</b>
                                    <span>{{ magicLevel.level }}</span>
                                </div>
                            </div>

                            <div class="skill" v-if="distance && isPaladin">
                                <i class="mdi mdi-sword icon"></i>
                                <div class="data">
                                    <b>Distance fighting</b>
                                    <span>{{ distance.level }}</span>
                                </div>
                            </div>

                            <div class="skill" v-if="meleeSkill && isKnight">
                                <i class="mdi mdi-sword icon"></i>
                                <div class="data">
                                    <b>{{ meleeSkill.skill | capitalize }} fighting</b>
                                    <span>{{ meleeSkill.level }}</span>
                                </div>
                            </div>

                            <div class="skill" v-if="shielding">
                                <i class="mdi mdi-shield icon"></i>
                                <div class="data">
                                    <b>Shielding</b>
                                    <span>{{ shielding.level }}</span>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <div class="alert alert-primary">
                                <i class="mdi mdi-emoticon-sad margin-right-5"></i>
                                Sorry, your skills can't be tracked.
                            </div>
                        </div>
                    </div>
                </div>
            </panel>
        </div>

        <div class="col-md-3">
            <panel class="character stats">
                <div class="stat">
                    <i class="mdi mdi-heart icon"></i>
                    <div class="data">
                        <b>HP</b>
                        <span>{{ hitpoints.format() }}</span>
                    </div>
                </div>
            </panel>

            <panel class="character stats">
                <div class="stat">
                    <i class="mdi mdi-speedometer icon"></i>
                    <div class="data">
                        <b>Speed</b>
                        <span>{{ speed.format() }}</span>
                    </div>
                </div>
            </panel>
        </div>

        <div class="col-md-3">
            <panel class="character stats">
                <div class="stat">
                    <i class="mdi mdi-flask-empty icon"></i>
                    <div class="data">
                        <b>Mana</b>
                        <span>{{ manapoints.format() }}</span>
                    </div>
                </div>
            </panel>

            <panel class="character stats">
                <div class="stat">
                    <i class="mdi mdi-weight icon"></i>
                    <div class="data">
                        <b>Capacity</b>
                        <span>{{ capacity.format() }}</span>
                    </div>
                </div>
            </panel>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['experience'],

        computed: {
            player () {
                return this.$store.getters['player/GET_PLAYER']
            },

            skills () {
                return this.$store.getters['player/GET_SKILLS']
            },

            level () {
                return this.$store.getters['player/GET_LEVEL']
            },

            hitpoints () {
                switch (this.player.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return (this.player.level - 8) * 15 + 185
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.player.level - 8) * 10 + 185
                    default:
                        return this.player.level * 5 + 145
                }
            },

            manapoints () {
                switch (this.player.vocation) {
                    case 'Druid':
                    case 'Sorcerer':
                    case 'Elder Druid':
                    case 'Master Sorcerer':
                        return (this.player.level - 8) * 30 + 90
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.player.level - 8) * 15 + 90
                    default:
                        return (this.player.level - 8) * 5 + 50
                }
            },

            speed () {
                return this.player.level + 109
            },

            capacity () {
                switch (this.player.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return (this.player.level - 8) * 25 + 470
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.player.level - 8) * 20 + 470
                    default:
                        return (this.player.level - 8) * 10 + 400
                }
            },

            magicLevel () {
                return this.skills.filter(skill => skill.skill == 'magic')[0]
            },

            shielding () {
                return this.skills.filter(skill => skill.skill == 'shielding')[0]
            },

            distance () {
                return this.skills.filter(skill => skill.skill == 'distance')[0]
            },

            meleeSkill () {
                const melee = this.skills.filter(skill => skill.skill == 'axe' || skill.skill == 'club' || skill.skill == 'sword')
                    .sort((a, b) => b.level - a.level)

                return melee.length ? melee[0] : false
            },

            isPaladin () {
                return this.player.vocation == 'Paladin' || this.player.vocation == 'Royal Paladin'
            },

            isKnight () {
                return this.player.vocation == 'Knight' || this.player.vocation == 'Elite Knight'
            },

            levelPercentage () {
                if (! this.player) return 0
                if (! this.level) return 0
                if (this.player.level != this.level.level) return 0

                const next = this.player.level + 1
                const nextExperience = ((50 * Math.pow((next - 1), 3)) - (150 * Math.pow((next - 1), 2)) + (400 * (next - 1))) / 3
                const experience = this.level.experience - this.player.experience
                const total = nextExperience - this.player.experience

                return parseInt(experience * 100 / total)
            }
        },
    }
</script>