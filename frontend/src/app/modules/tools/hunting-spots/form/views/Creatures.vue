<template>
    <div>
        <div class="creature-options">
            <el-select v-model="creature"
                       placeholder="Search and Choose the creature"
                       no-match-text="Creature not found"
                       no-data-text="You need to type 1 character at least."
                       filterable
                       remote
                       :remote-method="findCreature"
                       default-first-option
                       @change="addCreature">
                <el-option v-for="creature in listOfCreatures" :value="creature"
                           :label="creature.title" :key="creature.id"/>
            </el-select>
        </div>

        <div class="creatures row" v-if="creatures.length">
            <div class="col-md-4" v-for="creature, index in creatures">
                <div class="creature">
                    <button class="btn btn-xs btn-delete"
                            @click.prevent="removeCreature(index)">
                        <i class="mdi mdi-close"></i>
                    </button>

                    <div class="thumb">
                        <img :src="image_path('creature', creature.id)">
                    </div>
                    <span class="name">
                        <span>{{ creature.title }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        props: ['creatures'],

        data () {
            return {
                creature: null,
                listOfCreatures: [],
            }
        },

        watch: {
            creatures () {
                this.$emit('update:creatures', this.creatures)
            }
        },

        methods: {
            findCreature: debounce(function (query) {
                if (query == null || query == '') {
                    this.listOfCreatures = null
                    return false
                }

                services.getCreatures(query)
                    .then(response => this.listOfCreatures = response.data)
            }),

            addCreature (creature) {
                if (creature != null) {
                    this.creatures.push(creature)
                }

                this.creature = null
            },

            removeCreature (index) {
                this.creatures.splice(index, 1)
            },
        }
    }
</script>