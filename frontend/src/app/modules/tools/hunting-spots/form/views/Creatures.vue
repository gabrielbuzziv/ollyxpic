<template>
    <div class="creaturesList chooseable">
        <div class="form">
            <el-select v-model="newCreature"
                       no-match-text="Item not found"
                       no-data-text="Items not found"
                       placeholder="Choose the Creature"
                       value-key="id"
                       popper-class="item-popper"
                       default-first-option
                       filterable
                       remote
                       :remote-method="getCreatures">
                <el-option v-for="creature in creaturesList" :value="creature" :label="creature.title"
                           :key="creature.id">
                    <div class="thumb">
                        <img :src="image_path('creature', creature.id)">
                    </div>

                    <div class="name">{{ creature.title }}</div>
                </el-option>
            </el-select>

            <button class="btn" @click.prevent="addItem">
                <i class="mdi mdi-plus-circle margin-right-5"></i>
                Add
            </button>
        </div>

        <div class="row chooseable-items">
            <div class="col-md-3" v-for="creature, index in creatures">
                <div class="item">
                    <div class="thumb">
                        <img :src="image_path('creature', creature.id)" alt="">
                    </div>

                    <div class="name">{{ creature.title }}</div>

                    <div class="amount">
                        <a href="#" class="btn-remove" @click.prevent="removeItem(index)">
                            remove
                        </a>
                    </div>
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
                newCreature: '',
                creaturesList: []
            }
        },

        watch: {
            creatures () {
                this.$emit('update:creatures', this.creatures)
            }
        },

        methods: {
            getCreatures: debounce(function (query) {
                if (query != '' && query != null) {
                    services.getCreatures(query)
                        .then(response => this.creaturesList = response.data)
                } else {
                    this.creaturesList = []
                }
            }, 300),

            addItem () {
                if (this.newCreature != null && this.newCreature != '') {
                    this.creatures.push(this.newCreature)
                    this.newCreature = ''
                }
            },

            removeItem (index) {
                this.creatures.splice(index, 1)
            }
        },
    }
</script>