<template>
    <div class="row">
        <div class="col-md-6">
            <panel class="character level-panel">
                <div class="details">
                    <div class="level">
                        <div class="data">
                            <span>Level</span>
                            <b>{{ character.level }}</b>
                        </div>
                        <el-progress :percentage="getExperienceBar(level)" :show-text="false"
                                     :stroke-width="12"/>
                    </div>

                    <div class="skills" v-if="skills.length">
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

                        <div class="skill" v-if="meleeSkill">
                            <i class="mdi mdi-sword icon"></i>
                            <div class="data">
                                <b>{{ meleeSkill.label }} fighting</b>
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

                    <div class="skills" v-else>
                        <div class="alert alert-primary">
                            <i class="mdi mdi-emoticon-sad margin-right-5"></i>
                            Sorry, we only track skills from top 300.
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
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    export default {
        props: ['character', 'experience', 'skills'],

        computed: {
            level () {
                return this.experience.slice().sort((a, b) => b.id - a.id)[0]
            },

            hitpoints () {
                switch (this.character.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return (this.character.level - 8) * 15 + 185
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.character.level - 8) * 10 + 185
                    default:
                        return this.character.level * 5 + 145
                }
            },

            manapoints () {
                switch (this.character.vocation) {
                    case 'Druid':
                    case 'Sorcerer':
                    case 'Elder Druid':
                    case 'Master Sorcerer':
                        return (this.character.level - 8) * 30 + 90
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.character.level - 8) * 15 + 90
                    default:
                        return (this.character.level - 8) * 5 + 50
                }
            },

            speed () {
                return this.character.level + 109
            },

            capacity () {
                switch (this.character.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return (this.character.level - 8) * 25 + 470
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.character.level - 8) * 20 + 470
                    default:
                        return (this.character.level - 8) * 10 + 400
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

                return false

//                if (this.character.vocation == 'Paladin' || this.character.vocation == 'Royal Paladin') {
//                    const distance = this.skills.distance ? this.skills.distance : 0
//                    const distanceLevel = distance ? distance.level : 0
//
//                    if (distanceLevel) {
//                        distance['label'] = 'Distance'
//                        return distance
//                    }
//
//                    return 0
//                }
//
//                if (this.character.vocation == 'Knight' || this.character.vocation == 'Elite Knight') {
//                    const axe = this.skills.axe ? this.skills.axe : 0
//                    const club = this.skills.club ? this.skills.club : 0
//                    const sword = this.skills.sword ? this.skills.sword : 0
//
//                    const axeLevel = axe ? axe.level : 0
//                    const clubLevel = club ? club.level : 0
//                    const swordLevel = sword ? sword.level : 0
//
//                    const bigger = Math.max(axeLevel, clubLevel, swordLevel)
//
//                    switch (true) {
//                        case axe.level == bigger:
//                            axe['label'] = 'Axe'
//                            return axe
//                        case club.level == bigger:
//                            club['label'] = 'Club'
//                            return club
//                        case sword.level == bigger:
//                            sword['label'] = 'Sword'
//                            return sword
//                    }
//                }
//
//                return 0
            },

            isPaladin () {
                return this.character.vocation == 'Paladin' || this.character.vocation == 'Royal Paladin'
            }
        },

        methods: {
            getExperienceBar (advance) {
                if (this.experience.length) {
                    const nextLevel = advance.level + 1
                    const nextLevelExp = ((50 * Math.pow((nextLevel - 1), 3)) - (150 * Math.pow((nextLevel - 1), 2)) + (400 * (nextLevel - 1))) / 3
                    const currentExp = advance.experience
                    const expToNextLevel = 50 * Math.pow(advance.level, 2) - 150 * advance.level + 200
                    const expLeft = nextLevelExp - currentExp
                    const currentLeveledExp = expToNextLevel - expLeft
                    const percentage = parseInt((currentLeveledExp * 100) / expToNextLevel)

                    return this.experience
                        ? percentage
                        : 0
                }

                return 0
            }
        }
    }
</script>